@extends('layouts.app')
<!-- Navbar -->


<!-- /.navbar -->

<!-- Main Sidebar Container -->
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Главная</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Главная</a></li>
                            <li class="breadcrumb-item active">Поставщики</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">

                                <div style="margin-left: auto;"> <!-- Блок для ссылок справа -->
                                    @if(session('yml'))
                                        <a href="{{ session('yml') }}" target="_blank" style="margin-right: 40px;">Ссылка на goods.yml</a>
                                    @endif

                                    @if(session('xml'))
                                        <a href="{{ session('xml') }}" target="_blank">Ссылка на goods.xml</a>
                                    @endif
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Цена</th>
                                        <th>Категория</th>
                                        <th>Описание</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($goods as $good)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$good->title}}</td>
                                            <td>{{$good->price?->amount / 100}}</td>
                                            <td>{{\Illuminate\Support\Str::limit($good->category?->name, 50)}}</td>
                                            <td>{{\Illuminate\Support\Str::limit($good->description, 60)}}</td>
                                            <td><a data-toggle="modal" data-target="#exampleModal{{$good->id}}" href="">Обновить</a>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$good->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Редактирования
                                                            товара</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('update', $good->id)}}" method="post">
                                                            @csrf
                                                            <div class="">
                                                                <label for="">Цена</label>
                                                                <input class="form-control" name="amount" type="text"
                                                                       value="{{$good->price->amount / 100}}">
                                                            </div>
                                                            <div>
                                                                <label for="">Категория</label>
                                                                <select name="category_id" class="form-control" name=""
                                                                        id="">
                                                                    @foreach($categories as $category)
                                                                        <option
                                                                            {{$category->id == $good->category_id ? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Закрыть
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Сохранить
                                                            изменения
                                                        </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {{ $goods->links() }}

                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


