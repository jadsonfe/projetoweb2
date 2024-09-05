@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Livros</h1>
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'bibliotecario'))
        <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Adicionar Novo Livro</a>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editora</th>
                    <th>Categorias</th>
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'bibliotecario'))    
                    <th>Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->name }}</td>
                        <td>{{ $book->publisher->name }}</td>
                        @foreach ($book->categories as $category)
                        <td>
                            
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            
                        </td>
                        @endforeach
                        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'bibliotecario'))
                        <td>
                        
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</button>
                            </form>
                            
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
