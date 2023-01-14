<?php

namespace BestSnipp\Eden\Console;

use App\Models\Car;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeveloperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:developer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Running Developer Command');


//        $model = Phone::class;
//        $record = app($model);

        $model = User::class;
        $record = app($model);

        //$allRecords = $record->phone()->getRelated()->all();

        dd(
            //$record->phone()->newRelatedInstanceFor($record)->toSql()
            //$record->phone()->getEager()
            //$record->phone()->getQuery()->toSql()

            //$record->phone()->getRelated()->all()
            //$record->user()->getRelated()->all()

//            $record->users()->getForeignKeyName(),
//            $record->users()->getOwnerKeyName(),

//            $record->phone()->getForeignKeyName(),
//            $record->phone()->getLocalKeyName(),

            $record->posts()->getRelatedKeyName(),

        );

        // Work With Authorization
//        Auth::login(User::latest()->first());
//        $user = auth()->user();
//        $car = Car::latest()->first();
//
//        dd(Gate::has('accessEden'));
//
//        dd(Gate::getPolicyFor(Car::class), Gate::getPolicyFor($car));
//        $isAllowed = Gate::inspect('view', $car);
//        dd($user->can('view', $car));
//
//        if ($isAllowed->allowed()) {
//            dd('ALLOWED ...');
//        } else {
//            dd($isAllowed->message());
//        }

        $this->info('==> DONE');

        return 0;
    }
}
