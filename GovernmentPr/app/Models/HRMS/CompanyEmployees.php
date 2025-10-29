<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmployees extends Model
{
    use HasFactory;
    protected $table = 'company_employees';
    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'CompanyID',
        'FirstName',
        'LastName',
        'Email',
        'PhoneNumber',
        'DateOfBirth',
        'Gender',
        'JobTitle',
        'DepartmentID',
        // 'ManagerID',
        'HireDate',
        'Status',
        'Address',
        'City',
        'State',
        'ZipCode',
        'Country',
        'EmergencyContact',
        'EmergencyPhone',
        'ProfilePicture',
        'EmployeeNumber',
        'password',
        'LastLogin',
        'is_delete',
        'deleted_at'
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'DateOfBirth' => 'date',
        'HireDate' => 'date',
        'LastLogin' => 'datetime',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyID', 'CompanyID');
    }
    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class, 'DepartmentID', 'DepartmentID');
    }
    // public function manager()
    // {
    //     return $this->belongsTo(CompanyEmployees::class, 'ManagerID', 'EmployeeID');
    // }
    public function trainings()
    {
        return $this->hasMany(CompanyTraining::class, 'EmployeeID', 'EmployeeID');
    }
    public function recruitments()
    {
        return $this->hasMany(CompanyRecruitment::class, 'EmployeeID', 'EmployeeID');
    }
    public function performanceReviews()
    {
        return $this->hasMany(CompanyPerformanceReview::class, 'EmployeeID', 'EmployeeID');
    }
    public function attendanceRecords()
    {
        return $this->hasMany(CompanyAttendance::class, 'EmployeeID', 'EmployeeID');
    }
    public function leaveRequests()
    {
        return $this->hasMany(CompanyLeaveRequest::class, 'EmployeeID', 'EmployeeID');
    }
    public function payrollRecords()
    {
        return $this->hasMany(CompanyPayroll::class, 'EmployeeID', 'EmployeeID');
    }
}
