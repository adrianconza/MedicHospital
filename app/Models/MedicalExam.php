<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MedicalExam extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The result of the medical exam.
     *
     * @var array
     */
    const RESULTS = [
        'BN' => 'Bien',
        'RG' => 'Regular',
        'ML' => 'Mal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'result',
    ];

    /**
     * Get all medical exams without results.
     *
     * @param string|null $patient
     * @return array
     */
    public static function medicalExamsWithoutResult(string $patient = null)
    {
        if ($patient) {
            return DB::select("select distinct a.id, concat(p.name, ' ', p.last_name) patient, concat(u.name, ' ', u.last_name) doctor, mr.created_at
                from appointments a
                inner join patients p on a.patient_id = p.id
                inner join users u on a.user_id = u.id
                inner join medical_records mr on a.id = mr.appointment_id
                inner join medical_exams me on mr.id = me.medical_record_id
                where me.result is null and concat(p.name, ' ', p.last_name) like :patient
                order by mr.created_at",
                ['patient' => '%' . $patient . '%']);
        }
        return DB::select("select distinct a.id, concat(p.name, ' ', p.last_name) patient, concat(u.name, ' ', u.last_name) doctor, mr.created_at
                from appointments a
                inner join patients p on a.patient_id = p.id
                inner join users u on a.user_id = u.id
                inner join medical_records mr on a.id = mr.appointment_id
                inner join medical_exams me on mr.id = me.medical_record_id
                where me.result is null
                order by mr.created_at");
    }

    /**
     * Get all medical exams without results and appointment id.
     *
     * @param int $appointmentId
     * @return array
     */
    public static function medicalExamsWithoutResultAndAppointmentId(int $appointmentId)
    {
        return DB::select("select me.*, le.name laboratory_exam_name, ie.name imaging_exam_name
                from medical_exams me
                inner join medical_records mr on me.medical_record_id = mr.id
                left join laboratory_exams le on me.laboratory_exam_id = le.id
                left join imaging_exams ie on me.imaging_exam_id = ie.id
                where me.result is null and mr.appointment_id = :appointmentId
                order by mr.created_at", ['appointmentId' => $appointmentId]);
    }

    /**
     * Get the imaging exam for the medical exam.
     */
    public function imagingExam()
    {
        return $this->belongsTo(ImagingExam::class);
    }

    /**
     * Get the laboratory exam for the medical exam.
     */
    public function laboratoryExam()
    {
        return $this->belongsTo(LaboratoryExam::class);
    }

    /**
     * Get the medical record for the medical exam.
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
