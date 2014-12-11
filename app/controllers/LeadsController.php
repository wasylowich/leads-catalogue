<?php

use Models\Lead;
use Models\NewsletterSubscription;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LeadsController extends \BaseController {

    const LEADS_PER_PAGE = 6;

    /**
     * Display a listing of leads
     *
     * @return Response
     */
    public function index()
    {
        $query = Lead::with('newsletterSubscription');

        if (Input::has('search_term')) {
            $searchTerm = Input::get('search_term');

            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('phone', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
        }

        $leads = $query->paginate(self::LEADS_PER_PAGE);

        $data['leads'] = $leads->getCollection()->toArray();
        $data['links'] = $leads->links()->render();

        // Need to fix the links if searching
        if (Input::has('search_term')) {
            $data['links'] = str_replace("?page=", "?search_term={$searchTerm}&page=", $data['links']);
        }

        return Response::json($data);
    }

    /**
     * Show the form for creating a new lead
     *
     * @return Response
     */
    public function create()
    {
        return View::make('leads.create');
    }

    /**
     * Store a newly created lead in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Lead::$rules);

        if ($validator->fails())
        {
            return Response::json([], 400);
        }

        $lead = Lead::create($data);

        if (Input::get('newsletter') == 1) {
            NewsletterSubscription::create([
                'lead_id' => $lead->id,
                'format'  => Input::get('newsletter_format')
            ]);
        }

        $leads = Lead::with('newsletterSubscription')->paginate(3);

        $data['leads'] = $leads->getCollection()->toArray();
        $data['links'] = $leads->links()->render();

        return Response::json($data);
    }

    /**
     * Display the specified lead.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $lead = Lead::with('NewsletterSubscription')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Response::json([], 404);
        }

        return Response::json($lead->toArray());
    }

    /**
     * Show the form for editing the specified lead.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $lead = Lead::find($id);

        return View::make('leads.edit', compact('lead'));
    }

    /**
     * Update the specified lead in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $lead = Lead::findOrFail($id);
        $subscription = $lead->newsletterSubscription;

        $validator = Validator::make($data = Input::all(), Lead::$rules);

        if ($validator->fails())
        {
            return Response::json([], 400);
        }

        $lead->update($data);

        switch($lead->newsletter) {
            case 0:
                if ($subscription) {
                    NewsletterSubscription::destroy($subscription->id);
                }
                break;
            default:
                if ($subscription) {
                    $subscription->update($data);
                } else {
                    NewsletterSubscription::create([
                        'lead_id' => $lead->id,
                        'format'  => Input::get('newsletter_format')
                    ]);
                }
        }

        return Response::json($lead->toArray());
    }

    /**
     * Remove the specified lead from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);

        $subscription = $lead->newsletterSubscription;

        if ($subscription) {
            NewsletterSubscription::destroy($subscription->id);
        }

        Lead::destroy($id);

        $leads = Lead::with('newsletterSubscription')->get();

        return Response::json($leads->toArray());
    }

}
