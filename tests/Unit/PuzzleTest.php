<?php

namespace Tests\Unit;

use App\Models\Puzzle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PuzzleTest extends TestCase
{
    use RefreshDatabase;

    public function test_puzzle_can_be_created()
    {
        $puzzle = Puzzle::factory()->create([
            'nom'         => 'Test Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            'image'       => 'test_image.png',
        ]);

        $this->assertDatabaseHas('puzzles', [
            'nom' => 'Test Puzzle',
        ]);
    }

    public function test_puzzle_creation_fails_with_missing_data()
    {
        $this->expectException(ValidationException::class);

        $puzzleData = [
            'nom'         => '',
            'categorie'   => '',
            'description' => '',
            'prix'        => '',
            'image'       => '',
        ];

        $validator = Validator::make($puzzleData, [
            'nom'         => 'required',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric',
            'image'       => 'required',
        ]);

        $validator->validate();

        Puzzle::create($puzzleData);
    }

    public function test_puzzle_creation_fails_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $puzzleData = [
            'nom'         => str_repeat('A', 256), // trop long
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => -5.99, // prix négatif
            'image'       => 'test_image.png',
        ];

        $validator = Validator::make($puzzleData, [
            'nom'         => 'required|max:255',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric|min:0',
            'image'       => 'required',
        ]);

        $validator->validate();

        Puzzle::create($puzzleData);
    }

    public function test_puzzle_creation_fails_with_duplicate_data()
    {
        $puzzleData = [
            'nom'         => 'Unique Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            'image'       => 'test_image.png',
        ];

        Puzzle::create($puzzleData);

        $this->expectException(ValidationException::class);

        $validator = Validator::make($puzzleData, [
            'nom'         => 'required|unique:puzzles,nom',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric|min:0',
            'image'       => 'required',
        ]);

        $validator->validate();

        Puzzle::create($puzzleData);
    }
    

    public function test_puzzle_can_be_read()
    {
        $puzzle = Puzzle::factory()->create([
            'nom'         => 'Test Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            'image'       => 'test_image.png',
        ]);

        $foundPuzzle = Puzzle::find($puzzle->id);

        // Assertions pour vérifier que le puzzle existe et que les données sont correctes
        $this->assertNotNull($foundPuzzle);
        $this->assertEquals('Test Puzzle', $foundPuzzle->nom);
        $this->assertDatabaseHas('puzzles', [
            'nom' => 'Test Puzzle',
        ]);
    }


    public function test_puzzle_can_be_updated()
    {
        $puzzle = Puzzle::factory()->create();

        $puzzle->update([
            'nom' => 'Puzzle Mis à Jour',
        ]);

        $this->assertEquals('Puzzle Mis à Jour', $puzzle->fresh()->nom);
        $this->assertDatabaseHas('puzzles', [
            'nom' => 'Puzzle Mis à Jour',
        ]);
    }

    public function test_puzzle_can_be_deleted()
{
    $puzzle = Puzzle::factory()->create();

    $puzzle->delete();

    $this->assertDeleted($puzzle); 
    $this->assertDatabaseMissing('puzzles', [
        'id' => $puzzle->id,
    ]);
    $this->assertEquals('Puzzle Supprimé', $puzzle->fresh()->nom);
    $this->assertDatabaseHas('puzzles', [
        'nom' => 'Puzzle Supprimé',
    ]);
}



}
