<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;



// FOR SIMPLICITY WE WON'T IMPLEMENT DELETE OPERATIONS.

/*
 * TODO: Get all students list. ALREADY IMPLEMENTED IN THE DEMO SESSION.
 * URL: GET /students
 * Response:
     Status code: 200
     JSON body: 
         { 
           "data": [    
              { 
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              },
              { 
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              }
           ]
        }
 */
Route::get('/students', function (Request $request) {
    $rawData = DB::select(DB::raw("select id, name, email, phone from students"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'id' => $rd->id,
            'name' => $rd->name,
            'email' => $rd->email,
            'phone' => $rd->phone,
        ]);
    }

    $statusCode = 200;

    return response()->json([  
            'data' => $responseData
    ], $statusCode);
});


/* 
    * TODO: Create new student.
    * URL: POST /students
    * Request Body:
        {   
            "name": "student_name",
            "email": "student_email",
            "phone": "student_phone"
        }
    * Response:
        status_code: 200
        JSON body: 
            {   
                "data": {   
                    "id": "student_id_from_database"
                }
            }
*/

// Create new student
Route::post('/students', function (Request $request) {
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
    ]);

    $studentId = DB::table('students')->insertGetId($data);

    return response()->json([
        'data' => ['id' => $studentId,],
    ]);
});
/* 
    * TODO: Get student details by id
    * URL: GET /students/{id}
    * Response:
       * success:
            status_code: 200
            JSON body: 
                { 
                    "data": {
                        "id": "student_id",
                        "name": "student_name",
                        "email": "student_email",
                        "phone": "student_phone"
                    }
                }
       * not found:
            status_code: 404
            JSON body: 
                {   
                    "data": {}
                }
*/
Route::get('/students/{id}', function ($id) {
    $student = DB::table('students')->find($id);

    if ($student) {
        return response()->json([
            'data' => $student,
        ]);
    } else {
        return response()->json([
            'data' => [],
        ], 404);
    }
});
/*
    * TODO: Update student data
    * URL: PUT /students/{id}
    * Request Body:
        {   
            "name": "new_student_name",
            "email": "new_student_email",
            "phone": "new_student_phone"
        }
    * Response:
        status_code: 200
        JSON body:
            {   
                "data": {   
                    "id": "student_id",
                    "name": "new_student_name",
                    "email": "new_student_email",
                    "phone": "new_student_phone"
                }
            }
 */
Route::put('/students/{id}', function (Request $request, $id) {
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
    ]);

    $student = DB::table('students')->find($id);

    if (!$student) {
        return response()->json([
            'data' => [],
        ], 404);
    }

    DB::table('students')->where('id', $id)->update($data);

    return response()->json([
        'data' => [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ],
    ]);
});

 /*
   * TODO: For Courses implement Get, Create & Update endpoints same as students 
   * Get all URL: GET /courses
   * Get course details: GET /courses/{id}
   * Create new course: POST /courses{id}
   * Update course: PUT /courses/{id} 
   * Note: For JSON keys in both the request and response, let's use the same database columns names.
 */
 
 // Get all courses
 Route::get('/courses', function () {
     $rawData = DB::select(DB::raw("SELECT id, name FROM courses"));
 
     $responseData = [];
 
     foreach ($rawData as $rd) {
         array_push($responseData, [
             'id' => $rd->id,
             'name' => $rd->name,
         ]);
     }
 
     return response()->json(['data' => $responseData,]);
 });
 
 // Create new course
 Route::post('/courses', function (Request $request) {
     $data = $request->validate([
         'name' => 'required',
     ]);
 
     $courseId = DB::table('courses')->insertGetId($data);
 
     return response()->json([
         'data' => [
             'id' => $courseId,
         ],
     ]);
 });
 
 // Get course details by id
 Route::get('/courses/{id}', function ($id) {
     $course = DB::table('courses')->find($id);
 
     if ($course) {
         return response()->json(['data' => $course,]);
     } else {
         return response()->json(['data' => [],], 404);
     }
 });
 
 // Update course data
 Route::put('/courses/{id}', function (Request $request, $id) {
     $data = $request->validate([
         'name' => 'required',
     ]);
 
     $course = DB::table('courses')->find($id);
 
     if (!$course) {
         return response()->json(['data' => [],], 404);
     }
 
     DB::table('courses')->where('id', $id)->update($data);
 
     return response()->json([
         'data' => ['id' => $id, 'name' => $data['name'],],
     ]);
 });
 
 /*
  * TODO: Get all grades endpoint
  * URL: GET /grades
  * Response:
        status_code: 200
        JSON body: {    
            "data": [   
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */

// Get all grades
Route::get('/grades', function () {
    $rawData = DB::select(DB::raw("SELECT student_id, course_id, grade FROM grades"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'student_id' => $rd->student_id,
            'course_id' => $rd->course_id,
            'grade' => $rd->grade,
        ]);
    }

    return response()->json(['data' => $responseData,]);
});



  /*
   * TODO: Get grades for specific student only.
   * URL: GET /students/{student_id}/grades
   * Response:
        status_code: 200
        JSON body: {    
            "data": [   
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {   
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */


// Get grades for specific student
Route::get('/students/{student_id}/grades', function ($studentId) {
    $rawData = DB::select(DB::raw("SELECT student_id, course_id, grade FROM grades WHERE student_id = :student_id"), ['student_id' => $studentId]);

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'student_id' => $rd->student_id,
            'course_id' => $rd->course_id,
            'grade' => $rd->grade,
        ]);
    }

    return response()->json(['data' => $responseData]);
});


  /*
   * TODO: Get specific grade for specific student only. Shall return one record only if exists.
   * URL: GET /students/{student_id}/grades/{grade_id}
   * Response:
        status_code: 200
        JSON body: {    
            "data": {   
                "student_id": "STUDENT ID"
                "course_id": "COURSE ID",
                "grade": "GRADE"
            }
        }
  */
  // Get specific grade for specific student
Route::get('/students/{student_id}/grades/{grade_id}', function ($studentId, $gradeId) {
    $grade = DB::select(DB::raw("SELECT student_id, course_id, grade FROM grades WHERE student_id = ? AND grade_id = ?", [$studentId, $gradeId]));

    if ($grade) {
        return response()->json(['data' => $grade,]);
    } else {
        return response()->json(['data' => [],], 404);
    }
});