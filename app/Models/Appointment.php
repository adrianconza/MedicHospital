<?php

namespace App\Models;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Exception;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    const TIME = 30;

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
            $startTime->year = $dateAppointment->year;
            $startTime->month = $dateAppointment->month;
            $startTime->day = $dateAppointment->day;

            $endTime = new Carbon($attentionSchedule->end_time);
            $endTime->year = $dateAppointment->year;
            $endTime->month = $dateAppointment->month;
            $endTime->day = $dateAppointment->day;

            $diffInMinutes = $startTime->diffInMinutes($endTime);

            for ($i = 0; $i < $diffInMinutes / Appointment::TIME; $i++) {
                $minutesAdd = Appointment::TIME * $i;
                $newAppointmentStartTime = $startTime->copy()->add(new DateInterval("PT{$minutesAdd}M"));
                $newAppointmentEndTime = $newAppointmentStartTime->copy()->add(new DateInterval("PT" . Appointment::TIME . "M"));
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
}
