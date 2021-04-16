<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The qualify of the appointment
     *
     * @var array
     */
    const TYPE = [
        'TN' => 'Turno normal',
        'TE' => 'Turno extra',
    ];

    /**
     * The time of the appointment.
     * This time is in minutes
     *
     * @var int
     */
    const TIME = 30;

    /**
     * The extra time for input or view information.
     * This time is in minutes
     *
     * @var int
     */
    const EXTRA_TIME = 30;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'reason',
    ];

    /**
     * Generate appointments for all doctors.
     *
     * @param string $dayAppointment
     * @param MedicalSpeciality $medicalSpeciality
     * @return array
     * @throws Exception
     */
    public static function generateAppointments(string $dayAppointment, MedicalSpeciality $medicalSpeciality)
    {
        $appointments = [];

        $doctors = User::doctorsByMedicalSpeciality($medicalSpeciality->id);
        foreach ($doctors as $clave => $valor) {
            $doctorAppointments = Appointment::generateDoctorAppointments($dayAppointment, $valor->id, $medicalSpeciality);
            if ($doctorAppointments) {
                $appointments = array_merge($appointments, $doctorAppointments);
            }
        }

        if ($appointments) {
            array_multisort(array_column($appointments, 'start_time'), SORT_ASC,
                array_column($appointments, 'doctor'), SORT_ASC,
                $appointments);
        }

        return $appointments;
    }

    /**
     * Generate appointments for a doctor.
     *
     * @param string $dayAppointment
     * @param string $doctorId
     * @param MedicalSpeciality $medicalSpeciality
     * @return array
     * @throws Exception
     */
    public static function generateDoctorAppointments(string $dayAppointment, string $doctorId, MedicalSpeciality $medicalSpeciality)
    {
        $appointments = null;
        $doctor = User::find($doctorId);
        $dateAppointment = new Carbon($dayAppointment);
        $startDay = $dateAppointment->startOfDay()->toDateTimeString();
        $endDay = $dateAppointment->endOfDay()->toDateTimeString();

        $doctorAppointments = DB::select('select a.start_time, a.end_time
                    from appointments a
                    inner join users u on u.id = a.user_id
                    where a.deleted_at is null and u.id = :user_id and a.start_time >= :start_day and a.start_time <= :end_day
                    order by start_time',
            ['user_id' => $doctor->id, 'start_day' => $startDay, 'end_day' => $endDay]);
        foreach ($doctor->attentionSchedules as $attentionSchedule) {
            $startTime = new Carbon($attentionSchedule->start_time);
            $startTime->day = $dateAppointment->day;
            $startTime->month = $dateAppointment->month;
            $startTime->year = $dateAppointment->year;

            $endTime = new Carbon($attentionSchedule->end_time);
            $endTime->day = $dateAppointment->day;
            $endTime->month = $dateAppointment->month;
            $endTime->year = $dateAppointment->year;

            $diffInMinutes = $startTime->diffInMinutes($endTime);

            for ($i = 0; $i < $diffInMinutes / Appointment::TIME; $i++) {
                $minutesAdd = Appointment::TIME * $i;
                $newAppointmentStartTime = $startTime->copy()->addMinutes($minutesAdd);
                $newAppointmentEndTime = $newAppointmentStartTime->copy()->addMinutes(Appointment::TIME);
                $existAppointment = false;

                foreach ($doctorAppointments as $doctorAppointmentClave => $doctorAppointmentValor) {
                    $appointmentStartTime = new Carbon($doctorAppointmentValor->start_time);
                    $appointmentEndTime = new Carbon($doctorAppointmentValor->end_time);
                    if ($appointmentStartTime->eq($newAppointmentStartTime) && $appointmentEndTime->eq($newAppointmentEndTime)) {
                        $existAppointment = true;
                        break;
                    }
                }

                if (!$existAppointment && $newAppointmentStartTime->gte(Carbon::now())) {
                    $appointments[] = (object)[
                        'start_time' => $newAppointmentStartTime,
                        'end_time' => $newAppointmentEndTime,
                        'duration' => Appointment::TIME,
                        'doctor_id' => $doctor->id,
                        'doctor' => "{$doctor->name} {$doctor->last_name}",
                        'medical_speciality_id' => $medicalSpeciality->id,
                        'medical_speciality' => $medicalSpeciality->name,
                    ];
                }
            }
        }

        if ($appointments) {
            array_multisort(array_column($appointments, 'start_time'), SORT_ASC, $appointments);
        }

        return $appointments;
    }

    /**
     * Generate reports of appointments and qualifications.
     *
     * @return array
     */
    public static function generateReportAppointmentsAndQualifications()
    {
        return DB::select('select u.id, u.name, u.last_name, count(mr.id) num_appointments,
                       round(coalesce(avg(
                           case mr.qualify
                               when "RG" then 1
                               when "BN" then 2
                               when "EX" then 3
                           END
                       ), 0), 2) avg_qualify
                from users u
                inner join role_user ru on u.id = ru.user_id
                inner join roles r on ru.role_id = r.id
                left join appointments a on u.id = a.user_id
                left join medical_records mr on a.id = mr.appointment_id
                where r.name = :role
                group by u.id, u.name, u.last_name
                order by u.name, u.last_name',
            ['role' => Role::DOCTOR]);
    }

    /**
     * Get the patient for the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medical speciality for the appointment.
     */
    public function medicalSpeciality()
    {
        return $this->belongsTo(MedicalSpeciality::class);
    }

    /**
     * Get the doctor for the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medical records for the appointment.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Validate that the appointment is the valid time.
     *
     * @return bool
     */
    public function validTime()
    {
        $startTime = new Carbon($this->start_time);
        $endTime = new Carbon($this->end_time);
        $appointmentStartTime = $startTime->copy()->subMinutes(Appointment::EXTRA_TIME);
        $appointmentEndTime = $endTime->copy()->addMinutes(Appointment::EXTRA_TIME);
        return $appointmentStartTime->lte(Carbon::now()) && $appointmentEndTime->gte(Carbon::now());
    }
}
