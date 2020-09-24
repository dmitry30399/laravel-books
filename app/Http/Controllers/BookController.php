<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

// class BookController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//     {
//         //
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request)
//     {
//         //
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\Book  $book
//      * @return \Illuminate\Http\Response
//      */
//     public function show(Book $book)
//     {
//         //
//     }

    
//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\Book  $book
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(Book $book)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\Book  $book
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, Book $book)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\Book  $book
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Book $book)
//     {
//         //
//     }
// }

class BookController extends Controller
{
    // all books
    public function index()
    {
        $books = Book::all()->toArray();
        return array_reverse($books);
    }

    // add book
    public function add(Request $request)
    {
        $image = $request->file('image');
        $savedfileName = $this->storeImage($image);
        $imageUrl = $request->root() . '/storage/images/' . $savedfileName;

        $book = new Book([
            'title' => $request->input('title'),
            'isbn' => $request->input('isbn'),
            'description' => $request->input('description'),
            'image' => $imageUrl,
        ]);
        $book->save();

        return response()->json('The book successfully added');
    }

    // edit book
    public function edit($id)
    {
        $book = Book::find($id);
        return response()->json($book);
    }

    // update book
    public function update($id, Request $request)
    {
        $book = Book::find($id);
        $book->update($request->all());

        return response()->json('The book successfully updated');
    }

    // delete book
    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();

        return response()->json('The book successfully deleted');
    }

    /**
     * Prepares a image for storing.
     *
     * @param mixed $request
     * @return bool
     */
    public function storeImage($image) {
        // Get the original image extension
        $extension = $image->getClientOriginalExtension();
  
        // Create unique file name
        $fileNameToStore = time().'.'.$extension;
  
        // Refer image to method resizeImage
        $save = $this->resizeImage($image, $fileNameToStore);

        if ($save) {
            return $fileNameToStore;
        }
  
        return false;
    }

    /**
     * Resizes a image using the InterventionImage package.
     *
     * @param object $file
     * @param string $fileNameToStore
     * @return bool
     */
    public function resizeImage($file, $fileNameToStore) {
        // Resize image
        $resize = Image::make($file)->resize(200, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
  
        // Put image to storage
        $save = Storage::put("public/images/{$fileNameToStore}", $resize->__toString());
  
        if($save) {
          return true;
        }
        return false;
    }
}