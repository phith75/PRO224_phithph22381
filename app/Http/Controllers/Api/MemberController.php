<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\member;
use App\Http\Requests\StorememberRequest;
use App\Http\Requests\UpdatememberRequest;
use App\Http\Resources\MemberResource;
use App\Http\Resources\Book_ticketResource;
use App\Models\Book_ticket;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $data = member::all();
        return MemberResource::collection($data);
    }
    public function store(Request $request)
    {
        $bookTicket = Book_ticket::create($request->all());
        return new Book_ticketResource($bookTicket);
    }
}
