<?php

$html = '';
$html .= '<h1>Experts</h1>';

foreach ($experts as $expert) {
    $html .= '<div class="card my-2" style="width: 18rem;">';
        $html .= '<img src="'.OPE_ENV.'/uploads/experts/'.$expert['fileName'].'" class="card-img-top" alt="'.$expert['username'].'">';
        $html .= '<div class="card-body">';
            $html .= '<h5 class="card-title">'.$expert['firstName'].' '.$expert['lastName'].'</h5>';
            $html .= '<p class="card-text">Tarif : '.$expert['tarif'].' € par jour</p>';
            $html .= '<ul>';
            foreach($expert['aicores'] as $aicore){
                $html .= '<li class="small">'.$aicore['name'].'</li>';
            }
            $html .= '</ul>';
            $html .= '<a href="/experts/?username='.$expert['username'].'" class="btn btn-primary">Voir le profil</a>';
        $html .= '</div>';
    $html .= '</div>';
}


echo $html;
