<?php
/**
*   Classe que irá gerar os LOGS
*   @author Guilherme Brito
*   @version 1.0
*/
namespace GORM;
trait Utils
{

	/**
	 *  Função para gerar logs das informações passadas como parametro
	 * @param String $string
	 * @return void
	 */
	public function Error($string){
		//Cria o arquivo se ele não existir
		$handle = fopen("backend.log", "a");
		//Escrevo no arquivo aberto
		$data = date('d/m/Y G:i:s');
		fwrite($handle, "\n".$data." [ERROR]: ".$string);
		//fecha o arquivo;
		fclose($handle);
	}
	/**
	 *  Função para gerar logs das informações passadas como parametro
	 * @param String $string
	 * @return void
	 */
	public function Message($string){
		//Cria o arquivo se ele não existir
		$handle = fopen("backend.log", "a");
		//Escrevo no arquivo aberto
		$data = date('d/m/Y G:i:s');
		fwrite($handle, "\n".$data." [MESSAGE]:".$string);
		//fecha o arquivo;
		fclose($handle);
	}
	/**
	 *  Função para gerar logs das informações passadas como parametro
	 * @param String $string
	 * @return void
	 */
	public function Debug($string){
		//Cria o arquivo se ele não existir
		$handle = fopen("backend.log", "a");
		//Escrevo no arquivo aberto
		$data = date('d/m/Y G:i:s');
		fwrite($handle, "\n".$data." [DEBUG]:".$string);
		//fecha o arquivo;
		fclose($handle);
	}
}
