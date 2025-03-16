@extends('layout.app')
@section('content')
<h1 class="mb-5 h1 text-2xl font-bold">Review For: {{$book->title}}</h1>

<form action="{{route('books.reviews.store',$book)}}" method="post">
@csrf
<label class="label mb-5" for="review">Review</label>
<textarea class='input' name="review" id="review" rows="4" placeholder="write a review..">{{old('review')}}</textarea>
@error('review') <div class='text-red-500'>{{$message}}</div>  @enderror
<label class='label mt-5' for="rating">Rating</label>
<select class= 'input' name="rating" id="rating">
    <option value="">Select Rating</option>
    @for ($i=1 ;$i<=5;$i++)
    <option value="{{$i}}">{{$i}}</option>
    @endfor
</select>
@error('rating') <div class='text-red-500'>{{$message}}</div>  @enderror
<button class="btn input mt-5" type="submit">Add</button>
</form>
@endsection
