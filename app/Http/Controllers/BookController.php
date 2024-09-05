<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; // Importar Gate, caso precise para algumas verificações específicas

class BookController extends Controller
{
    // Função para exibir uma lista de livros
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'categories'])->get();
        return view('books.index', compact('books'));
    }

    // Função para exibir um livro específico
    public function show($id)
{
    $book = Book::with(['author', 'publisher', 'categories'])->findOrFail($id);
    
    // Verifica se o usuário tem permissão para visualizar o livro
    $this->authorize('view', $book);

    return view('books.show', compact('book'));
}


    // Função para exibir o formulário de criação de um novo livro
    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('books.create', compact('authors', 'publishers', 'categories'));
    }

    // Função para armazenar um novo livro no banco de dados
    public function store(Request $request)
{
    // Verifica se o usuário tem permissão para criar um livro
    $this->authorize('create', Book::class);

    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'author_id' => 'required|integer',
        'publisher_id' => 'required|integer',
        'published_year' => 'required|integer',
        'categories' => 'required|array',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $book = Book::create($validatedData);
    $book->title = $request->input('title');
    $book->categories()->attach($request->categories);

    if ($request->hasFile('cover_image')) {
        $file = $request->file('cover_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/covers', $filename); // Armazena na pasta public/covers
        $book->cover_image = $filename;
    }

    $book->save();

    return redirect()->route('books.index')->with('success', 'Livro criado com sucesso!');
}


    // Função para exibir o formulário de edição de um livro
    public function edit($id)
    {
        $book = Book::findOrFail($id);
    
        // Verifica se o usuário tem permissão para editar o livro
        $this->authorize('update', $book);
    
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('books.edit', compact('book', 'authors', 'publishers', 'categories'));
    }
    

    // Função para atualizar um livro no banco de dados
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'author_id' => 'required|integer',
        'publisher_id' => 'required|integer',
        'published_year' => 'required|integer',
        'categories' => 'required|array',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    $book = Book::findOrFail($id);
    
    // Verifica se o usuário tem permissão para atualizar o livro
    $this->authorize('update', $book);
    
    // Verifique se há uma nova imagem de capa
    if ($request->hasFile('cover_image')) {
        // Remova a imagem antiga se existir
        if ($book->cover_image) {
            $oldPath = 'public/covers/' . $book->cover_image;
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }
    
        // Armazene a nova imagem e atualize o nome da imagem
        $file = $request->file('cover_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/covers', $filename);
    
        // Atualize o nome da imagem no banco de dados
        $book->cover_image = $filename;
    }

    // Atualize os outros atributos do livro
    $book->fill($validatedData);
    $book->categories()->sync($request->categories);
    $book->save();

    return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso!');
}

    
    
    

    
    // Função para excluir um livro do banco de dados
    public function destroy($id)
{
    $book = Book::findOrFail($id);
    
    // Verifica se o usuário tem permissão para excluir o livro
    $this->authorize('delete', $book);

    $book->categories()->detach();
    if ($book->cover_image) {
        Storage::delete('public/covers/' . $book->cover_image);
    }
    $book->delete();

    return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso!');
}


   
}
