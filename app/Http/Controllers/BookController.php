<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Validator;
use Auth;
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(Book::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        if( $request->addType == "audio" ){
            $validatedData = Validator::make($request->all(),[
         
                'name' => 'required|string',
                'description' => 'required|string',
                'type' => 'required',
                'edition' => 'required',
                'langue' => 'required',
                'son' => 'required',
                'cover' => 'required'
               
    
              
            ]);
        }else{
            $validatedData = Validator::make($request->all(),[
         
                'name' => 'required|string',
                'description' => 'required|string',
                'type' => 'required',
                'edition' => 'required',
                'langue' => 'required',
                'pdf' => 'required',
                'cover' => 'required'
               
    
              
            ]);
        }
      

        
        if ($validatedData->fails())
        {
            return response()->json($validatedData->errors(), 201);
            //return redirect()->back()->withErrors();
        }

        $book = new Book();
        $book->name = $request->name;
        $book->edition = $request->edition;
        $book->type = $request->type;
        $book->langue = $request->langue;
        $book->description = $request->description;
        $book->user_id = Auth::user()->id;
//dd($request->addType);
        if( $request->addType == "pdf" ){
            $PDFfile = $request->file('pdf');
            $newPDFFileName = uniqid().'.'.$PDFfile->getClientOriginalExtension();
            //Move Uploaded File
            $destinationPath = 'uploads/livres/pdf';
            $PDFfile->move($destinationPath,$newPDFFileName);
            $book->pdf =   $newPDFFileName;
            
        }else{
            $SONfile = $request->file('son');
            $newSONFileName = uniqid().'.'.$SONfile->getClientOriginalExtension();
           //Move Uploaded File
            $destinationPath = 'uploads/livres/son';
            $SONfile->move($destinationPath,$newSONFileName);
            $book->son =   $newSONFileName;
           
        }
      


        
        $coverfile = $request->file('cover');
        $newcoverFileName = uniqid().'.'.$coverfile->getClientOriginalExtension();
        //Move Uploaded File
        $destinationPath = 'uploads/livres/cover';
        $coverfile->move($destinationPath,$newcoverFileName);
        $book->cover =   $newcoverFileName;

         if ( $book->save() ){
                    return response()->json($book, 200);
         }else{
            return response()->json(["message" => "impossible d'\ajoutee ce livre"], 400);
         }

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($idbook)
    {
        //
         return response()->json(Book::find($idbook), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        

        if( $request->addType == "audio" ){
            $validatedData = Validator::make($request->all(),[
         
                'name' => 'required|string',
                'description' => 'required|string',
                'type' => 'required',
                'edition' => 'required',
                'langue' => 'required',
              
               
    
              
            ]);
        }else{
            $validatedData = Validator::make($request->all(),[
         
                'name' => 'required|string',
                'description' => 'required|string',
                'type' => 'required',
                'edition' => 'required',
                'langue' => 'required',
               
               
    
              
            ]);
        }
      

        
        if ($validatedData->fails())
        {
            return response()->json($validatedData->errors(), 201);
            //return redirect()->back()->withErrors();
        }

        $book = Book::find($request->id);
        $book->name = $request->name;
        $book->edition = $request->edition;
        $book->type = $request->type;
        $book->langue = $request->langue;
        $book->description = $request->description;

        if( $request->addType == "pdf" ){
            if ($request->file('pdf')){
                $PDFfile = $request->file('pdf');
                $newPDFFileName = uniqid().'.'.$PDFfile->getClientOriginalExtension();
                //Move Uploaded File
                $destinationPath = 'uploads/livres/pdf';
                $PDFfile->move($destinationPath,$newPDFFileName);
                $book->pdf =   $newPDFFileName;
            }
            
            
        }else{
            if( $request->file('son')){
                $SONfile = $request->file('son');
                $newSONFileName = uniqid().'.'.$SONfile->getClientOriginalExtension();
               //Move Uploaded File
                $destinationPath = 'uploads/livres/son';
                $SONfile->move($destinationPath,$newSONFileName);
                $book->son =   $newSONFileName;
            }
          
           
        }
      


        if ($request->file('cover')){
            $coverfile = $request->file('cover');
            $newcoverFileName = uniqid().'.'.$coverfile->getClientOriginalExtension();
            //Move Uploaded File
            $destinationPath = 'uploads/livres/cover';
            $coverfile->move($destinationPath,$newcoverFileName);
            $book->cover =   $newcoverFileName;
    
        }
        
         if ( $book->update() ){
                    return response()->json($book, 200);
         }else{
            return response()->json(["message" => "impossible de modifier ce livre"], 400);
         }

        //

       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($idbook)
    {
        //
        if (Book::find($idbook)->delete()){
            return response()->json(["message" => "livre supprimer"], 200);
        }else{
            return response()->json(["message" => "impossible de supprimer ce livre"], 400);
        }
    }

    public function getAudioBooks(){
        return response()->json(Book::where('son','!=',null)->get(), 200); 
    }
    public function getpdfBooks(){
        return response()->json(Book::where('pdf','!=',null)->get(), 200); 
    }

    public function getAudioBooksSearch(Request $request){
        if( $request->langue != "" && $request->type != "" ){
            return response()->json(
                Book::where('son','!=',null)
                ->where('type',$request->type)
                ->where('langue',$request->langue)
                ->get(), 200);
        }else if( $request->langue != "" ) {
            return response()->json(
                Book::where('son','!=',null)
                ->where('langue',$request->langue)
                ->get(), 200);

        }else if ( $request->type != "" ){
            return response()->json(
                Book::where('son','!=',null)
                ->where('type',$request->type)
                ->get(), 200);

        }
        return response()->json(
            Book::where('son','!=',null)->get(), 200);
         
        }
    
        public function getPDFBooksSearch(Request $request){
            if( $request->langue != "" && $request->type != "" ){
                return response()->json(
                    Book::where('pdf','!=',null)
                    ->where('type',$request->type)
                    ->where('langue',$request->langue)
                    ->get(), 200);
            }else if( $request->langue != "" ) {
                return response()->json(
                    Book::where('pdf','!=',null)
                    ->where('langue',$request->langue)
                    ->get(), 200);
    
            }else if ( $request->type != "" ){
                return response()->json(
                    Book::where('pdf','!=',null)
                    ->where('type',$request->type)
                    ->get(), 200);
    
            }
            return response()->json(
                Book::where('pdf','!=',null)->get(), 200);
             
            }

        
}
