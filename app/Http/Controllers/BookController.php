<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter');



        $books = Book::when('title',fn($query,)=>$query->title($title));

        $books = match($filter){
            'popular-last-month' => $books->popularLastMonth(),
            'popular-last-6months' => $books->popularLast6Months(),
            'high-rated-last-month' => $books->highRatedLastMonth(),
            'high-rated-last-6months' => $books->highRatedLast6Months(),
            default => $books->latest()->withPopular()->withHighRated()
        };

        //Return Books From Database
         // $books = $books->get();
        //@dd($books);

        $cachKey = "book:".$title.':'.$filter;
         //Return Books From Cache
        $books = cache()->remember($cachKey,3600,fn()=>$books->get());

        return view('books.index',['books'=>$books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $cachKey = 'book:'. $id;
        $book = cache()->remember(
            $cachKey,
            3600,
            fn()=>
            Book::with([
            'reviews'=>fn($query)=>$query->latest()
            ])->withPopular()->withHighRated()->find($id));
            
        return view('books.show',['book'=>$book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
