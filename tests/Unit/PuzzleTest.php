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

    public function test_puzzle_can_be_created(): void
    {
        $puzzle = Puzzle::factory()->create([
            'nom'         => 'Test Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            'image'       => 'test_image.png',
        ]);

        $this->assertDatabaseHas('puzzles', ['nom' => 'Test Puzzle']);
        $this->assertInstanceOf(Puzzle::class, $puzzle);
    }

    public function test_puzzle_creation_fails_with_missing_data(): void
    {
        $this->expectException(ValidationException::class);

        $input = [
            'nom'         => '',
            'categorie'   => '',
            'description' => '',
            'prix'        => '',
            'image'       => '',
        ];

        $validator = Validator::make($input, [
            'nom'         => 'required',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric',
            'image'       => 'required',
        ]);

        // Lève ValidationException
        $validator->validate();
    }

    public function test_puzzle_creation_fails_with_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $input = [
            'nom'         => str_repeat('A', 256),
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => -5.99,
            'image'       => 'test_image.png',
        ];

        $validator = Validator::make($input, [
            'nom'         => 'required|max:255',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric|min:0',
            'image'       => 'required',
        ]);

        $validator->validate();
    }

    public function test_puzzle_creation_fails_with_duplicate_data(): void
    {
        // 1er enregistrement OK
        Puzzle::create([
            'nom'         => 'Unique Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            'image'       => 'test_image.png',
        ]);

        // 2e payload avec le même nom -> doit échouer à la validation
        $this->expectException(ValidationException::class);

        $dup = [
            'nom'         => 'Unique Puzzle', // dupliqué
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 10.50,
            'image'       => 'img2.png',
        ];

        $validator = Validator::make($dup, [
            'nom'         => 'required|unique:puzzles,nom',
            'categorie'   => 'required',
            'description' => 'required',
            'prix'        => 'required|numeric|min:0',
            'image'       => 'required',
        ]);

        $validator->validate();
    }

    public function test_puzzle_can_be_read(): void
    {
        $puzzle = Puzzle::factory()->create([
            'nom'         => 'Test Puzzle',
            'categorie'   => 'Test Categorie',
            'description' => 'Ceci est un puzzle de test.',
            'prix'        => 9.99,
            // image vient de la factory si tu ne la fournis pas ici
        ]);

        $found = Puzzle::find($puzzle->id);

        $this->assertNotNull($found);
        $this->assertEquals($puzzle->nom, $found->nom);
        $this->assertEquals($puzzle->categorie, $found->categorie);
    }

    public function test_puzzle_can_be_updated(): void
    {
        $puzzle = Puzzle::factory()->create(['nom' => 'Ancien nom']);

        $puzzle->update(['nom' => 'Nom mis à jour']);

        $this->assertDatabaseHas('puzzles', ['id' => $puzzle->id, 'nom' => 'Nom mis à jour']);
        $this->assertDatabaseMissing('puzzles', ['id' => $puzzle->id, 'nom' => 'Ancien nom']);
    }

    public function test_puzzle_can_be_deleted(): void
    {
        $puzzle = Puzzle::factory()->create();

        $puzzle->delete();

        $this->assertModelMissing($puzzle);
        $this->assertDatabaseMissing('puzzles', ['id' => $puzzle->id]);
    }
}
