<?php
	require "inc_commun.php";
	require "header_et_menu.php";
	$idSortie=$_GET["id"];
	$sortie = sortie::charger($idSortie, $stock);
	$bUtiliseBeneficiaire=($stock->utiliseBeneficiaire=="O");
?>
<script type="text/javascript">
function rendreVirtuelle(idSortie) {
	if (confirm('Etes vous sur(e) de vouloir rendre VIRTUELLE cette sortie ?\nLes articles seront ré-intégrés dans le stock.')) {
		window.location="consulterSortie_trt.php?action=RendreVirtuelle&idSortie=" + idSortie;
	}
}
function rendreReelle(idSortie) {
	if (confirm('Etes vous sur(e) de vouloir rendre REELLE cette sortie ?\nLes articles seront retirés du stock.')) {
		window.location="consulterSortie_trt.php?action=RendreReelle&idSortie=" + idSortie;
	}
}

function modifier(idSortie, etat) {
	if (etat=="<?php echo sortie::$REELLE;?>") {
		alert('ATTENTION : avant de pouvoir modifier une sortie, vous devez la rendre virtuelle.');
	} else {
		window.location="modifierSortie.php?id=" + idSortie;
	}
}
</script>

<CENTER>

<br>
<h1>Sortie : <?php echo $sortie->nom;?></h1>
<br>
Etat : <b><?php echo $sortie->libelleEtat();?></b>
<br><br>
<table class="tableCommune">
<tr><th align="left">Date (jj/mm/aaaa)&nbsp;&nbsp;&nbsp;</th><td><?php echo $sortie->date;?></td></tr>
<tr><th align="left">Commentaire&nbsp;&nbsp;&nbsp;</th><td><?php echo str_replace("\n", "<br>", $sortie->commentaire);?></td></tr>
</table>
<br><br>
<table class="tableCommune">
<tr><th>Nom</th><?php if ($bUtiliseBeneficiaire) echo "<th>Bénéficiaire</th>";?><th>Prix<br>unitaire</th><th>Quantité</th><th>Prix<br>total</th></tr>
<?php
	foreach ($sortie->tLigneSortie as $ligneSortie) {
		$nom=$ligneSortie->article->nom;
		$prixSortie=$ligneSortie->prixSortie;
		$quantite=$ligneSortie->quantite;
		$prixTotal=$prixSortie * $quantite;
		$prixTotal=number_format($prixTotal, 2, '.', ' ');
		echo "<tr>";
		echo "<td>$nom</td>";
		if ($bUtiliseBeneficiaire) echo "<td>$ligneSortie->beneficiaire</td>";
		echo "<td class=\"tdPrix\">$prixSortie</td><td class=\"tdQuantite\">$quantite</td><td class=\"tdPrix\">$prixTotal</td></tr>";
	}
	$colSpan=($bUtiliseBeneficiaire?4:3);
	echo "<tr><td colspan='$colSpan'>Total</td><td class=\"tdPrix\">$sortie->coutTotal</td></tr>";
?>
</table>
<br><br>
<?php
	if ($sortie->etat==sortie::$VIRTUELLE) {
		$changerEtat="Rendre REELLE";
		$fctChangeEtat="rendreReelle($sortie->idSortie)";	// Fonction javascript à appeler
	} else {
		$changerEtat="Rendre VIRTUELLE";
		$fctChangeEtat="rendreVirtuelle($sortie->idSortie)";	// Fonction javascript à appeler
	}
?>


<a class="menu" href="javascript:javascript:modifier(<?php echo $sortie->idSortie;?>, '<?php echo $sortie->etat;?>')">Modifier</a><br>
<a class="menu" href="javascript:<?php echo $fctChangeEtat;?>"><?php echo $changerEtat;?></a><br>
<a class="menu" href="consulterSorties.php">Retour à la liste des sorties</a><br>
</CENTER>
<br><br><br><br>
<?php
	require "footer.php";
?>
