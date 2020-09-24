<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Image;

class BookController extends Controller
{
    // List books
    public function index(Request $request)
    {
        $currentPage = $request->query('current_page', 1);
        $perPage = $request->query('per_page', 15);
        $books = Book::orderBy('updated_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
        return response()->json($books);
    }

    // add book
    public function add(Request $request)
    {
        $image = $request->file('image');
        $savedfileName = $this->storeImage($image);
        $imageUrl = '/storage/images/' . $savedfileName;

        $book = new Book([
            'title' => $request->input('title'),
            'isbn' => $request->input('isbn'),
            'description' => $request->input('description'),
            'image' => $imageUrl,
        ]);
        $book->save();

        return response()->json('The book successfully added');
    }

    // Get a book by id
    public function edit($id)
    {
        $book = Book::find($id);
        
        return response()->json($book);
    }

    // Update book
    public function update($id, Request $request)
    {
        $book = Book::find($id);
        $book->update($request->all());

        return response()->json('The book successfully updated');
    }

    // Delete book
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
    public function storeImage($image)
    {
        // Create unique file name
        $fileNameToStore = time().'.jpg';
  
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
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resize = Image::make($file)->resize(200, 400)->encode('jpg');
  
        // Put image to storage
        $save = Storage::put("public/images/{$fileNameToStore}", $resize->__toString());
  
        if($save) {
          return true;
        }
        return false;
    }
}