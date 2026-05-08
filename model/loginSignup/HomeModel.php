<?php

class HomeModel {

    public function getServices(){
        return [
            [
                "title" => "Document Requests",
                "desc" => "Request TOR, certificates, and good moral documents online",
                "time" => "1-10 days processing"
            ],
            [
                "title" => "Grade Consultations",
                "desc" => "Schedule consultations with faculty",
                "time" => "Quick response"
            ],
            [
                "title" => "Office Appointments",
                "desc" => "Book appointments with offices",
                "time" => "Flexible scheduling"
            ]
        ];
    }

}
