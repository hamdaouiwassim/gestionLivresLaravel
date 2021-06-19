<?php

namespace App\Http\Controllers;

use App\Commentaire;
use Illuminate\Http\Request;
use App\Book;
use App\User;
use Auth;
class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
        $comment = new Commentaire();
        $comment->user_id = Auth::user()->id;
        $comment->book_id = $request->book;
        $comment->content = $request->content;
        $comment->save();
        return response()->json($comment, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Commentaire  $commentaire
     * @return \Illuminate\Http\Response
     */
    public function show(Commentaire $commentaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Commentaire  $commentaire
     * @return \Illuminate\Http\Response
     */
    public function edit(Commentaire $commentaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Commentaire  $commentaire
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $comment = Commentaire::find($request->idcomment);
        $comment->content = $request->content;
        $comment->update();
        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commentaire  $commentaire
     * @return \Illuminate\Http\Response
     */
    public function destroy($idcommentaire)
    {
        //
        Commentaire::find($idcommentaire)->delete();
        return response()->json(['message'=>'Commentaire supprimer'], 200);
    }

    public function getBookComments($idbook){
        return response()->json(Book::find($idbook)->comments, 200);

    }
    public function getUsers(){
        return response()->json(User::where('role','client')->get(), 200);
    }
}
