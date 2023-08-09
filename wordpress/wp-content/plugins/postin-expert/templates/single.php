<?php

$html = '';

$html .= '<div class="row">';
    $html .= '<div class="col-5 text-center">';
        $html .= '<img src="'.OPE_ENV.'/uploads/experts/'.$expert['fileName'].'" class="img-fluid rounded" alt="'.$expert['username'].'">';
        $html .= '<small class="mb-lg-4">Tarif : '.$expert['tarif'].' € par jour</small>';
        $html .= '<hr>';
        foreach($expert['aicores'] as $aicore){
            $html .= '<p class="fs-8">'.$aicore['name'];
            $html .= '<br>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;" class="text-warning" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;" class="text-warning" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;" class="text-warning" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;" class="text-warning" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;" class="" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 1L12.39 6.75H19.78L14.76 10.25L17.17 16L10 12.75L2.83 16L5.24 10.25L0.22 6.75H7.61L10 1Z" />
            </svg>
            </p>
            ';
        }
    $html .= '</div>';
    $html .= '<div class="col-7">';
        $html .= '<h1>'.$expert['firstName'].' '.$expert['lastName'].'</h1>';
        $html .= '<p>'.$expert['bio'].'</p>';
        $html .= '<hr>';
        $html .= '<h6>Langages</h6>';
        foreach($expert['languages'] as $language){
            $html .= '<p><span class="text-primary">'.$language['title'].'</span>';
            $html .= ' ( <small><span class="text-secondary">'.$language['level'].'</span></small> ) </p>';
        }
        $html .= '<hr>';
        $html .= '<h6>Experiences</h6>';
        foreach($expert['experiences'] as $experience){
            $html .= '<p><span class="text-primary">'.$experience['title'].'</span> chez <span class="text-secondary">'.$experience['company'].'</span> à <span class="text-warning">'.$experience['location'].'</span></p>';
        }
    $html .= '</div>';
$html .= '</div>';

$html .= '<div class=""></div>';
$html .= '<div class=""></div>';
$html .= '<div class=""></div>';
$html .= '';
$html .= '';
$html .= '';
$html .= '';

echo $html;