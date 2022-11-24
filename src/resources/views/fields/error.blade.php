@if (!is_null($validator) && $validator->fails())
    <span class="bg-red-500 text-white w-full block px-3 py-2 -mt-1 rounded-br-md rounded-bl-md">{!! $validator->errors()->first() !!}</span>
@endif
