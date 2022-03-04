<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        // // From URL to get redirected URL
        // $url = 'https://picsum.photos/500/500.jpg';
        
        // // Initialize a CURL session.
        // $ch = curl_init();
        
        // // Grab URL and pass it to the variable.
        // curl_setopt($ch, CURLOPT_URL, $url);
        
        // // Catch output (do NOT print!)
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        // // Return follow location true
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        // $html = curl_exec($ch);
        
        // // Getinfo or redirected URL from effective URL
        // $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        
        // // Close handle
        // curl_close($ch);
        // // // echo "Original URL:   " . $url . "<br/>";
        // // echo "Redirected URL: " . $redirectedUrl . "<br/>";

        $sids = User::where('role', 'seller')->pluck('id')->toArray();
        $rand = array_rand($sids);

        return [
            'user_id' => $sids[$rand],
            'name' => $this->faker->word(5),
            'description' => $this->faker->paragraph(100),
            'image' => 'https://picsum.photos/id/' . rand(1, 1000) . '/500/500', //$redirectedUrl, //'https://picsum.photos/500/500.jpg',
            'price' => $this->faker->randomFloat(2, 10, 1000.00),
        ];
    }
}
