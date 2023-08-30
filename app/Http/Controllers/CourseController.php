<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\DestroyRequest;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;


class CourseController extends Controller
{
    private Builder $model;
    public function __construct(){
        $this->model = (new Course())->query();
        $routeName = Route::currentRouteName();
        $arr = explode('.', $routeName);
        $arr = array_map('ucfirst', $arr);
        $title = implode(' - ', $arr);
        View::share('title', $title);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Normal index
//        $search = $request->get('q');
//        $data = Course::query()
//            ->where('name','like','%' . $search . '%')
//            ->paginate(2);
//        $data->appends(['q' => $search]);

//        return view('course.index', [
//            'data' => $data,
//            'search' => $search,
//        ]);

        // index with laravel datatable
        return view('course.index');
    }

    public function api(){
         return Datatables::of($this->model)
             ->editColumn('created_at', function($object){
                return $object->year_created_at;
             })
//             ->addColumn('edit', function($object){
//                 $link = route('course.edit', $object) ;
//                 return "<a class='btn btn-primary' href='$link'>Sửa</a>";
//             })
//             ->rawColumns(['edit']) -- solution 1 to add collumn EDIT
              ->addColumn('edit', function ($object) {
                  return route('course.edit', $object);
             })
             ->addColumn('destroy', function ($object) {
                 return route('course.destroy', $object);
             })
             ->make(true);

//-- khong dung thu vien datatable
//        $data = $this->model->paginate(1, ['*'],'page', $request->get('draw'));
//
//        $arr = [];
//        $arr['draw'] = $data->currentPage();
//        $arr['data'] = [];
//        foreach ($data->items() as $item){
//            $item->setAppends([
//                'year_created_at',
//            ]);
//            $item->edit = route('course.edit', $item);
//            $item->delete = route('course.destroy', $item);
//
//            $arr['data'][] = $item;
//        }
//        $arr['recordsTotal'] = $data->total();
//        $arr['recordsFiltered'] = $data->total();
//
//        return $arr;
    }

    public function apiName(Request $request) {
        return $this->model->where('name', 'like','%' . $request->get('q') . '%')
        ->get([
           'id',
           'name',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
//        $object = new Course();
//        $object->fill($request->except('_token'));
//        $object->save();

//        Course::create($request->except('_token'));
        $this->model->create($request->validated());

        return redirect()->route('course.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('course.edit', [
            'each' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request,$courseId)
    {
//        Course::where('id',$course->id)->update(
//            $request->except([
//                '_token',
//                '_method',
//            ])
//        ); --- cach 1
//        $course->update(
//            $request->except([
//                '_token',
//                '_method'
//            ])
//        ); -- cach 2
//        Course::query()->where('id', $courseId)
//        ->where('user', auth()->id)
//        ->firstOrFail();

//        $this->model->where('id', $courseId)->update(
//          $request->validated()
//        );
//        $this->model->update(
//            $request->validated()
//        );
        $object = $this->model->find($courseId);

        $object->fill($request->validated());
        $object->save();

        return redirect()->route('course.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $courseId)
    {
        $this->model->where('id',$courseId)->delete();
//        $this->model->find(courseId)->delete();

        $arr = [];
        $arr['status'] = true;
        $arr['message'] = '';

//        return redirect()->route('course.index');
        return response($arr,200);
    }
}
