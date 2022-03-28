<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Tests\TestCase;

class HelperTest extends TestCase
{
    public function test_hex_code_only_has_one_or_zero_hashtag(){
        $this->assertNotEquals(true, is_hexcode("##FF2AA"));
        $this->assertNotEquals(true, is_hexcode("#3FF#AA"));
        $this->assertNotEquals(true, is_hexcode("#3FFA#A"));
        $this->assertNotEquals(true, is_hexcode("#3FFAA#"));
        $this->assertNotEquals(true, is_hexcode("##FFAA#"));
        $this->assertNotEquals(true, is_hexcode("######"));

        $this->assertTrue(is_hexcode("#AFD2CC"));
        $this->assertTrue(is_hexcode("AFD2CC"));
    }

    public function test_hex_code_has_six_digits(){
        $this->assertNotEquals(true, is_hexcode("#AFF2AA3"));
        $this->assertNotEquals(true, is_hexcode("#AFF2A"));
        $this->assertNotEquals(true, is_hexcode("FAFF2AA"));
        $this->assertNotEquals(true, is_hexcode("FAFA3"));

        $this->assertTrue(is_hexcode("#AFF2AA"));
        $this->assertTrue(is_hexcode("AFF2AA"));
    }

    public function test_hex_code_only_has_hex_digits(){
        //hex base(0-9 and A - F)
        $this->assertNotEquals(true, is_hexcode("#FF2AG3"));
        $this->assertNotEquals(true, is_hexcode("FG2AG3"));

        $this->assertTrue(is_hexcode("FF2AC3"));
        $this->assertTrue(is_hexcode("#FF2AF3"));
    }

}
