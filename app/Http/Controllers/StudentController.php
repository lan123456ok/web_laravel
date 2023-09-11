<?php

namespace App\Http\Controllers;

use App\Enums\StudentStatusEnum;
use App\Http\Requests\Student\StoreRequest;
use App\Models\Course;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    private Builder $model;

    public function __construct()
    {
        $this->model = (new Student())->query();
        $routeName = Route::currentRouteName();
        $arr = explode('.', $routeName);
        $arr = array_map('ucfirst', $arr);
        $title = implode(' - ', $arr);

        $arrStudentStatus = StudentStatusEnum::getArrayView();
//        dd($arrStudentStatus);

        View::share('title', $title);
        View::share('arrStudentStatus', $arrStudentStatus);
    }

    public function index()
    {
//        $student = $this->model->get();

        return view('student.index');
    }

    public function api(Request $request)
    {
//        $query = $this->model->select('students.*')
//            ->addSelect('courses.name as course_name')
//            ->join('courses','courses.id', 'students.course_id');
        // truyen query neu xai query de JOIN
        return Datatables::of($this->model->with('course'))
            ->addColumn('course_name', function ($object) {
                return $object->course->name;
            })
            ->editColumn('gender', function ($object) {
                return $object->genderName;
            })
            ->editColumn('status', function ($object) {
                return StudentStatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('age', function ($object) {
                return $object->age;
            })
            ->addColumn('edit', function ($object) {
                return route('student.edit', $object);
            })
            ->addColumn('student', function ($object) {
                return route('student.destroy', $object);
            })
            ->filterColumn('course_name', function($query, $keyword) {
                    $query->whereHas('course', function ($q) use ($keyword){
                        return $q->where('id', $keyword);
                    });
            })
//            back end xu ly
            ->filterColumn('status', function($query, $keyword) {
                if($keyword !== "all")
                    $query->where('status', $keyword);
            })
             ->make(true);
    }

    public function create()
    {
        $courses = Course::query()->get();
        return view('student.create',
            [
                'courses' => $courses,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $path = Storage::disk('public')->putFile('avatars',$request->file('avatar'));
        $arr = $request->validated();
        $arr['avatar'] = $path;
        $this->model->create($arr);

        return redirect()->route('student.index')->with('success', "Đã thêm");
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
