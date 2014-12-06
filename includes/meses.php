<?php

class Meses{
	public function convert_mes($mes_extenso){

		switch($mes_extenso){

			case 1; $mes_extenso = 'Janeiro'; break;
			case 2; $mes_extenso = 'Fevereiro'; break;
			case 3; $mes_extenso = 'Março'; break;
			case 4; $mes_extenso = 'Abril'; break;
			case 5; $mes_extenso = 'Maio'; break;
			case 6; $mes_extenso = 'Junho'; break;
			case 7; $mes_extenso = 'Julho'; break;
			case 8; $mes_extenso = 'Agosto'; break;
			case 9; $mes_extenso = 'Setembro'; break;
			case 10; $mes_extenso = 'Outubro'; break;
			case 11; $mes_extenso = 'Novembro'; break;
			case 12; $mes_extenso = 'Dezembro'; break;
		}

		return $mes_extenso;
	}
}

class MesesAbr{
	public function convert_mes_abr($mes_abr){

		switch($mes_abr){

			case 1; $mes_abr = 'Jan'; break;
			case 2; $mes_abr = 'Fev'; break;
			case 3; $mes_abr = 'Mar'; break;
			case 4; $mes_abr = 'Abr'; break;
			case 5; $mes_abr = 'Mai'; break;
			case 6; $mes_abr = 'Jun'; break;
			case 7; $mes_abr = 'Jul'; break;
			case 8; $mes_abr = 'Ago'; break;
			case 9; $mes_abr = 'Set'; break;
			case 10; $mes_abr = 'Out'; break;
			case 11; $mes_abr = 'Nov'; break;
			case 12; $mes_abr = 'Dez'; break;
		}

		return $mes_abr;
	}
}

class Semana{
	public function convert_semana($semana_extenso){

		switch($semana_extenso){

			case 'Sun'; $semana_extenso = 'Domingo'; break;
			case 'Mon'; $semana_extenso = 'Segunda'; break;
			case 'Tue'; $semana_extenso = 'Terça'; break;
			case 'Wed'; $semana_extenso = 'Quarta'; break;
			case 'Thu'; $semana_extenso = 'Quinta'; break;
			case 'Fri'; $semana_extenso = 'Sexta'; break;
			case 'Sat'; $semana_extenso = 'Sábado'; break;
		}

		return $semana_extenso;
	}
}

?>