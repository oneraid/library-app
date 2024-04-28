<?php

use Illuminate\Support\Facades\Route;
use App\Models\Book;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');

});

Route::get('/greeting', function() {
    return 'Hello World';
});

// Required Parameters
Route::get('/book/{id}', function(string $id) {
    return 'Book Id: ' .$id;
});

//Optional Parameters
Route::get('/book-title/{title?}', function (?string $title = 'Bumi Manusia'){
    return 'Book title: '.$title;
});

Route::get('/book-profile', function (){
    return 'Book Profile Page';
})->name('book-profile');

//Route Prefixes
Route::prefix('admin')->group(function () {

    Route::get('/books', function (){
        //sama dengan "/admin/books" URL
        return 'Semua buku dari halaman admin';
    });
    Route::get('/authors', function (){
        //sama dengan "/admin/authors" URL
        return 'Semua author dari halaman admin';
    });
});

Route::get('book-simple', function () {
    return view('book_simple', ['title' => 'Bumi Manusia']);
});

// ---------------------------------------------------------------------

// Route untuk menampilkan index view dengan data buku
Route::get('/books', function() {
    $books = Book::all();
    return view('books.index', ['books' => $books]);
});

// Route untuk menampilkan form nambah buku
Route::get('/books/create', function(){
    return view('books.create');
});

// Route untuk menyimpan data buku yang baru ditambahkan
Route::post('/books', function (Request $request){
    $validateData = $request->validate([
        'title' => 'required',
        'author' => 'required',
        'year' => 'required|numeric'
    ]);

    $book = new Book();
    $book->title = $validateData['title'];
    $book->author = $validateData['author'];
    $book->year = $validateData['year'];
    $book->save();

    return redirect()->route('books.index');
})->name('books.store');

// Route untuk menampilkan form edit buku
Route::get('/books/{id}/edit', function ($id){
    $book = Book::findOrFail($id);  //mengambil data berdasarkan ID
    return view('books.edit', ['book'  => $book]);
})->name('books.edit');

// Route untuk menyimpan data buku yang telah diubah
Route::put('/books/{id}', function (Request $request, $id){
    $validateData = $request->validate([
        'title' => 'required',
        'author' => 'required',
        'year' => 'required|numeric',
    ]);

    $book = Book::findOrFail($id); // mengambil data buku berdasarkan ID
    $book->title = $validateData['title'];
    $book->author = $validateData['author'];
    $book->year = $validateData['year'];
    $book->save();

    return redirect()->route('books.index');
})->name('books.update');

// Route untuk menghapus data buku
Route::delete('/books/{id}', function ($id) {
    $book = Book::findOrFail($id); //Mengambil data buku berdasarkan ID
    $book->delete ();

    return redirect()->route('books.index');
})->name('books.destroy');
