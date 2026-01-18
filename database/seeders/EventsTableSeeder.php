<?php

namespace Database\Seeders;

use App\Cost;
use App\Donation;
use App\Event;
use App\Registrant;
use App\Sponsor;
use App\Team;
use App\TeamMember;
use App\User;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('user_type', 'auth')->first();

        $event = Event::create([
            'title' => '2019 Czar’s Promise Dog Walk',
            'short' => '2019 Dog Walk',
            'event_date' => strtotime('May 11, 2019'),
            'description' => '&lt;p&gt;Czar&amp;rsquo;s Promise is excited to announce our inaugural Inspiring Hope dog walk! Join Czar&amp;rsquo;s Promise, our generous community partners, sponsors, vendors, participants and teams on Saturday, May 11, 2019, all in the name of helping companion animals and children in our community in their fight against cancer!&lt;/p&gt;

&lt;p&gt;This event is family and dog-friendly. Walk as an individual or join as a team!&lt;/p&gt;

&lt;div class=&quot;row&quot;&gt;
&lt;div class=&quot;col col-sm-6&quot;&gt;
&lt;h3&gt;&lt;span class=&quot;script&quot;&gt;When?&lt;/span&gt;&lt;/h3&gt;

&lt;p&gt;Saturday, May 11, 2019&lt;br /&gt;
10:00 am - 2:00 pm&lt;br /&gt;
Walk begins at 11:30 am&lt;/p&gt;
&lt;/div&gt;

&lt;div class=&quot;col col-sm-6&quot;&gt;
&lt;h3&gt;&lt;span class=&quot;script&quot;&gt;Where?&lt;/span&gt;&lt;/h3&gt;

&lt;p&gt;Winnequah Park&lt;br /&gt;
5301 Healy Lane&lt;br /&gt;
Monona, WI&lt;/p&gt;
&lt;/div&gt;
&lt;/div&gt;',
            'image' => 'fall-fundraising-walk-1537371007.jpg',
            'goal' => '100000',
        ]);

        Cost::create([
            'cost' => 25,
            'ends' => strtotime('january 1, 2019'),
            'event_id' => $event->id,
        ]);
        Cost::create([
            'cost' => 30,
            'ends' => strtotime('april 1, 2019'),
            'event_id' => $event->id,
        ]);
        Cost::create([
            'cost' => 35,
            'ends' => strtotime('may 11, 2019'),
            'event_id' => $event->id,
        ]);

        Sponsor::create([
            'event_id' => $event->id,
            'name' => 'Dog Grin Photography',
            'url' => 'https://doggrinphotography.com/',
            'filename' => 'dog-grin-photography-1539207228.jpg',
        ]);
        Sponsor::create([
            'event_id' => $event->id,
            'name' => 'Levy Giving Fund',
            'url' => 'https://www.levygivingfund.com/',
            'filename' => 'levy-giving-fund-1539207202.jpg',
        ]);
        Sponsor::create([
            'event_id' => $event->id,
            'name' => 'The Viney Family',
            'url' => 'https://www.facebook.com/beth.viney',
            'filename' => 'the-viney-family-1539207149.png',
        ]);
        Sponsor::create([
            'event_id' => $event->id,
            'name' => 'Veterinary Emergency Services',
            'url' => 'http://veterinaryemergencyservice.com/',
            'filename' => 'veterinary-emergency-services-1539207121.png',
        ]);
        Sponsor::create([
            'event_id' => $event->id,
            'name' => 'Edinger Surgical Options',
            'url' => 'https://edingersurgicaloptions.com/',
            'filename' => 'edinger-surgical-options-1539207073.jpg',
            'presenting' => 1,
        ]);

        /*
                Event::create([
                    'title' => 'Another Event',
                    'short' => 'Another Event',
                    'event_date' => strtotime('May 11, 2019'),
                    'description' => "&lt;p&gt;Coming Soon&lt;/p&gt;",
                    'image' => 'new-event-1539121786.jpg',
                    'goal' => '100000',
                ]);
        */

        /*
                $registrant = Registrant::create([
                    'event_id'=>$event->id,
                    'fname'=>'Test',
                    'lname'=>'User',
                    'email'=>'test3@voxcomp.com',
                    'phone'=>'123-123-1234',
                    'address'=>'123 Walkaway St',
                    'city'=>'Madison',
                    'state'=>'WI',
                    'zip'=>'12345',
                    'registrant'=>'adult',
                    'shirt'=>'l',
                    'pets'=>'1',
                    'paid'=>'35',
                    'goal'=>3500,
                    'pagetitle'=>'My Personal Page',
                    'pagecontent'=>'Not so good content that must be reviewed....'
                ]);

                $registrantuser = User::where('email',$registrant->email)->first();

                $registrant->update([
                    'user_id'=>$registrantuser->id
                ]);

                $registrantuser->update([
                    'fname'=>'Test',
                    'lname'=>'User',
                    'phone'=>'123-123-1234',
                    'address'=>'123 Walkaway St',
                    'city'=>'Madison',
                    'state'=>'WI',
                    'zip'=>'12345',
                ]);

                $team = Team::create([
                    'registrant_id'=>$registrant->id,
                    'event_id'=>$event->id,
                    'name'=>"Thrillers",
                    'goal'=>'6000',
                ]);

                TeamMember::create([
                    'team_id'=>$team->id,
                    'registrant_id'=>$registrant->id
                ]);

                Donation::create([
                    'fname'=>'Frank',
                    'lname'=>'Something',
                    'email'=>'test6@voxcomp.com',
                    'phone'=>'987-654-3210',
                    'amount'=>5000,
                    'registrant_id'=>$registrant->id,
                    'event_id'=>$event->id
                ]);

                Donation::create([
                    'fname'=>'Test',
                    'lname'=>'User',
                    'email'=>'test3@voxcomp.com',
                    'phone'=>'123-123-1234',
                    'address'=>'123 Walkaway St',
                    'city'=>'Madison',
                    'state'=>'WI',
                    'zip'=>'12345',
                    'join'=>0,
                    'amount'=>1000,
                    'event_id'=>$event->id
                ]);
        */
    }
}
