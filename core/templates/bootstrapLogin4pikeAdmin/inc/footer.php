<footer class="footer">
		<span class="text-center" data-toggle="tooltip" data-placement="top"
              title="<?= e(Sistema::$versao); ?>" data-original-title="<?= e(Sistema::$versao); ?>">
		 <?= e(date('Y')); ?> - <?= e(Sistema::$nome); ?><?= ((isset($usuario)) && !empty($usuario)) ? ' - <strong>' . $usuario->getNomeLotacao() : ''; ?></strong>
		</span>
</footer>

<!--<footer class="footer">
		<span class="text-right">
		Copyright <a target="_blank" href="#">Your Website</a>
		</span>
    <span class="float-right">
		Powered by <a target="_blank" href="https://www.pikeadmin.com"><b>Pike Admin</b></a>
		</span>
</footer>-->