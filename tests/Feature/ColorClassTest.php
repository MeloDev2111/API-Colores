<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Tests\TestCase;
use App\Models\Color;

class ColorClassTest extends TestCase
{
    public function test_hex_code_only_has_one_or_zero_hashtag(){
        $this->assertFalse(Color::is_hexcode("##FF2AA"));
        $this->assertFalse(Color::is_hexcode("#3FF#AA"));
        $this->assertFalse(Color::is_hexcode("#3FFA#A"));
        $this->assertFalse(Color::is_hexcode("#3FFAA#"));
        $this->assertFalse(Color::is_hexcode("##FFAA#"));
        $this->assertFalse(Color::is_hexcode("######"));

        $this->assertTrue(Color::is_hexcode("#AFD2CC"));
        $this->assertTrue(Color::is_hexcode("AFD2CC"));
    }

    public function test_hex_code_has_six_digits(){
        $this->assertFalse(Color::is_hexcode("#AFF2AA3"));
        $this->assertFalse(Color::is_hexcode("#AFF2A"));
        $this->assertFalse(Color::is_hexcode("FAFF2AA"));
        $this->assertFalse(Color::is_hexcode("FAFA3"));

        $this->assertTrue(Color::is_hexcode("#AFF2AA"));
        $this->assertTrue(Color::is_hexcode("AFF2AA"));
    }

    public function test_hex_code_only_has_hex_digits(){
        //hex base(0-9 and A - F)
        $this->assertFalse(Color::is_hexcode("#FF2AG3"));
        $this->assertFalse(Color::is_hexcode("FG2AG3"));

        $this->assertTrue(Color::is_hexcode("FF2AC3"));
        $this->assertTrue(Color::is_hexcode("#FF2AF3"));
    }

}
