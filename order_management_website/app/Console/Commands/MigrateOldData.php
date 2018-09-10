<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class MigrateOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bl:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old data';

    private $oldDB;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->oldDB = DB::connection('old_db');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->migratePack();
        $this->migrateCookie();
        $this->migrateCaseType();
        $this->migrateUser();
        $this->migrateOrder();
        $this->migrateCase();
        $this->migrateCaseContent();
        $this->migratePackage();
        $this->migratePackageContent();
    }

    private function migratePack()
    {
        $packs = $this->oldDB->select('SELECT * FROM Packing');
        $packs = collect($packs)->map(function($pack) {
            return [
                'id' => $pack->id,
                'name' => $pack->name ?: null,
                'enabled' => $pack->show ?: false,
                'slug' => $pack->shortname ?: null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('packs')->insert($packs->toArray());
    }

    private function migrateCookie()
    {
        $cookies = $this->oldDB->select('SELECT * FROM Cookie');
        $cookies = collect($cookies)->map(function($cookie) {
            return [
                'id' => $cookie->id,
                'name' => $cookie->name ?: null,
                'enabled' => $cookie->show ?: false,
                'slug' => $cookie->shortname ?: null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('cookies')->insert($cookies->toArray());
    }

    private function migrateCaseType()
    {
        $types = $this->oldDB->select('SELECT * FROM CaseType');
        $types = collect($types)->map(function($type) {
            return [
                'id' => $type->id,
                'name' => $type->name ?: null,
                'enabled' => $type->show ?: false,
                'slug' => $type->shortname ?: null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('case_types')->insert($types->toArray());
    }

    private function migrateUser()
    {
        $users = $this->oldDB->select('SELECT * FROM User');
        $users = collect($users)->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->username,
                'password' => $user->password,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('users')->insert($users->toArray());
    }

    private function migrateOrder()
    {
        $orders = $this->oldDB->select('SELECT * FROM `Order`');
        $orders = collect($orders)->map(function($order) {
            return [
                'id' => $order->id,
                'name' => $order->name,
                'name_backup' => $order->name_bak ?: null,
                'phone' => $order->phone ?: null,
                'phone_backup' => $order->phone_bak ?: null,
                'email' => $order->mail ?: null,
                'engaged_date' => $order->enage_date === '0000-00-00' ? null : $order->enage_date,
                'married_date' => $order->wedding_date === '0000-00-00' ? null : $order->wedding_date,
                'remark' => $order->remark ?: null,
                'deposit' => $order->prepaid,
                'final_paid' => $order->endpaid,
                'extra_fee' => $order->other_price,
                'card_required' => $order->needcard,
                'wood_required' => $order->needwood,
                'created_at' => $order->created_at ?: Carbon::now(),
                'updated_at' => $order->updated_at ?: Carbon::now(),
            ];
        });

        DB::table('orders')->insert($orders->toArray());
    }

    private function migrateCase()
    {
        $cases = $this->oldDB->select('SELECT * FROM `Case`');
        $cases = collect($cases)->map(function($case) {
            return [
                'id' => $case->id,
                'order_id' => $case->order_id,
                'case_type_id' => $case->casetype_id,
                'price' => $case->price,
                'amount' => $case->count,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('cases')->insert($cases->toArray());
    }

    private function migrateCaseContent()
    {
        $contents = $this->oldDB->select('SELECT * FROM `CaseContent`');
        $contents = collect($contents)->map(function($content) {
            return [
                'case_id' => $content->case_id,
                'pack_id' => $content->packing_id,
                'cookie_id' => $content->cookie_id,
                'amount' => $content->count
            ];
        });

        foreach (array_chunk($contents->toArray(), 1000) as $content) {
            DB::table('case_has_cookies')->insert($content);
        }
    }

    private function migratePackage()
    {
        $packages = $this->oldDB->select('SELECT * FROM `Package`');
        $packages = collect($packages)->map(function($package) {
            return [
                'id' => $package->id,
                'arrived_at' => $package->arrive_date === '0000-00-00' ? null : $package->arrive_date,
                'name' => $package->name,
                'phone' => $package->phone,
                'address' => $package->address,
                'remark' => $package->remark,
                'sent_at' => $package->send_date === '0000-00-00' ? null : $package->send_date,
                'order_id' => $package->order_id,
                'checked' => $package->checked,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('packages')->insert($packages->toArray());
    }

    private function migratePackageContent()
    {
        $contents = $this->oldDB->select('SELECT * FROM `PackageContent`');
        $contents = collect($contents)->map(function($content) {
            return [
                'case_id' => $content->case_id,
                'package_id' => $content->package_id,
                'amount' => $content->count
            ];
        });

        foreach (array_chunk($contents->toArray(), 1000) as $content) {
            DB::table('package_has_cases')->insert($content);
        }
    }
}
