<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = (new Question)->getQuestions()->get();
        return Inertia::render('Question/Index', [
            'questions' => $questions              
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quizzes = Quiz::latest()->get();
        return Inertia::render('Question/Create', ['quizzes' => $quizzes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $new_name2 = $image->storeAs('qImagess', $new_name);
        }
        $data = $request->validate([
            'quiz'  =>  'required',
            'question'  =>  'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            'options' => 'required',
            'correct_answer' => 'required',
        ]);
        $data['image'] = $new_name2;
        $question = (new Question)->storeQuestion($data);
        $answer = (new Answer)->storeAnswer($data, $question);
        return Redirect::route('quiz.edit', $question->quiz_id )->with('message', 'Success Question created...');
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return Inertia::render('Question/Edit', [
            'question' => [
                'id' => $question->id,
                'question' => $question->question,
                'image' => $question->image,
                'answers' => $question->answers()->get()->map->only('id', 'answer', 'is_correct'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        $question-> question = $request->get('question');      
        if($question->save()){
            $options = $request->get('options') ;

            foreach( $options as $key => $option){
                $answer = Answer::find($option['id']);
                    $answer-> answer = $option['answer'];
                    $answer-> is_correct = $option['is_correct'];
                $answer->save(); 
            };          
            return redirect()->back()->with('message', 'Success Question updated ....');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new Answer)->deleteAnswer($id);
        $question = Question::find($id);
        $question->delete();
        return Redirect::route('quiz.edit', $question->quiz_id)->with('message', 'Success Question and Choices deleted ....');
    }
}
