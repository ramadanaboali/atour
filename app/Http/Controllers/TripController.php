<?php
namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Attachment;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function update(TripRequest $request, Trip $trip)
    {
        $data = $request->validated();

        // Format available_times
        $available_times = [];
        foreach ($data['available_times']['from_time'] as $index => $from_time) {
            $available_times[] = [
                'from_time' => $from_time,
                'to_time' => $data['available_times']['to_time'][$index]
            ];
        }
        $data['available_times'] = json_encode($available_times);

        // Format available_days
        $data['available_days'] = json_encode(array_values($data['available_days']));

        // Handle file upload
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Update trip
        $trip->update($data);

        // Sync relationships
        $trip->requirements()->sync($data['requirement_ids']);
        $trip->subCategories()->sync($data['sub_category_ids']);
        $trip->features()->sync($data['featur_ids']);

        // Handle images
        if ($request->hasFile('images')) {
            // Delete existing attachments
            Attachment::where('model_id', $trip->id)->where('model_type', 'trip')->delete();

            // Save new images
            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $image->store('trip_images', 'public');
                $attachment = [
                    'model_id' => $trip->id,
                    'model_type' => 'trip',
                    'attachment' => $storedPath,
                    'title' => "trip",
                ];
                Attachment::create($attachment);
            }
        }

        flash(__('trips.messages.updated'))->success();
        // Redirect
        return redirect()->route('admin.trips.index');
    }
}
