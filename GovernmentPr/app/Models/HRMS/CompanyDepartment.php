<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use HasFactory;
    protected $table = 'company_departments';
    protected $primaryKey = 'DepartmentID';
    protected $fillable = [
        'DepartmentName',
        'ManagerIDs',
        'CompanyID',
        'Status'
    ];
    protected $casts = [
        'ManagerIDs' => 'array', // Assuming ManagerIDs is stored as a JSON array
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public $timestamps = true;
    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyID', 'CompanyID');
    }

    public function managers()
    {
        return $this->hasMany(CompanyEmployees::class, 'EmployeeID', 'ManagerIDs');
    }

    public function employees()  {
        return $this->hasMany(CompanyEmployees::class, 'DepartmentID', 'DepartmentID');
    }

}
