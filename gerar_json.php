<?php header("content-type: text/html; charset=utf-8");
		require_once("../componentes/includes/classes/funcoes.class.php");
		$Comando = new Funcao();
		$pais = $Comando->CypherQuery('MATCH (n:pais) RETURN n.nome as pais');
		$node = array(); 
		$link = array();
		$i = 0;
		foreach ($pais as $key) {
			$pa = array(); 
			$pa['name'] = $key['pais'];
			$pa['group'] = mt_rand(0, 10);
			$pa['ID'] = $i; 
			$pa['tipo'] = "País";			
			$i++;
			$node[] = $pa; 
		}
		$estado = $Comando->CypherQuery('MATCH (n:estado)-[r:pais]->(pa:pais) RETURN n.nome as es, pa.nome as pa');
		foreach ($estado as $key) {
			$es = array(); 
			$linkAUX = array();=
			$es['name'] = $key['es'];
			$es['group'] = mt_rand(11, 20);
			$es['ID'] = $i; 
			$es['tipo'] = "Estado";
			$paAUX = $key['pa'];
			foreach ($node as $key) 
			{
				if($key['name'] === $paAUX)
				{
					$numero = $key['ID'];
				}
			}
			$linkAUX['source'] = $i;
			$linkAUX['target'] = $numero; 
			$linkAUX['value'] = 1; 
			$link[] = $linkAUX;
			$node[] = $es; 
			$i++;
		}
		$moradores = $Comando->CypherQuery("MATCH (p:pessoa)-[r:end]->(end:estado) RETURN p.nome as nome, end.nome as es");
		foreach ($moradores as $key)
		{
			$user = array();
			$linkAUX = array();
			$user['name'] = $key['nome'];
			$user['ID'] = $i;
			$esAUX = $key['es']; 
			foreach ($node as $key) 
			{
				if($key['name'] === $esAUX)
				{
					$numero = $key['ID'];
					$group = $key['group'];
				}
			}
			$user['group'] = $group;
			$user['tipo'] = "Morador";
			$node[] = $user;
			$linkAUX['source'] = $i;
			$linkAUX['target'] = $numero; 
			$linkAUX['value'] = 1; 
			$link[] = $linkAUX;
			$i++;
		}
		$json['nodes'] = $node;
		$json['links'] = $link;
		$name = 'localizacao.json';
		$file = fopen($name, 'w',0);
		fwrite($file,json_encode($json, JSON_UNESCAPED_UNICODE));
		fclose($file);
?>
