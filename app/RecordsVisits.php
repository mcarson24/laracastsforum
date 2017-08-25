<?php

namespace App;

trait RecordsVisits
{
	public function visits()
    {
        return new Visit($this);   
    }    
}
