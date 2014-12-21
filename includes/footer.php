
<div class="container">
	<div class="page-header"></div>

	<footer>
		<p class="pull-right">
			<a href="#" data-toggle="modal" data-target="#myModalSobre">Sobre</a> &middot; 
			<a href="#" data-toggle="modal" data-target="#myModalTermos">Termos</a>
		</p>
        <p>&copy; 2012 - <?php echo date("Y") ?> Controle Financeiro <small>Versão 2</small> </p>
	</footer> <!-- /.footer -->
</div> <!-- /.container -->

<!-- Modal Sobre -->
<div class="modal fade" id="myModalSobre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Controle Financeiro - Sobre</h4>
			</div>
			<div class="modal-body">
				<h4>Sobre o sistema</h4>
				<p><strong>Controle Financeiro ©</strong> foi desenvolvido para você ter o controle total de suas contas, gerenciando suas despesas pessoais e tendo uma visão ampla de seus gastos de uma maneira precisa e detalhada.</p>

				<h4>Acesso</h4>
				<p>Quer utilizar o sistema para gerenciar suas despesas? <a href="">Clique aqui</a> e preencha o formulário de cadastro e aguarde que o mesmo seja aprovado pelo administrador do site.</p>

				<h4>Responsável</h4>
				<p>Design e programação: Aristides Neto</p>

				<h4>Sugestões e crítcas</h4>
				<p>Envie sugestões e/ou críticas para que o sistema <strong>Controle Financeiro ©</strong> melhore ainda mais.</p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Termos -->
<div class="modal fade" id="myModalTermos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Controle Financeiro - Termos</h4>
			</div>
			<div class="modal-body">
				<h4>Termos de Serviço</h4>
				<p>Ao acessar o site, você concorda estar de acordo com os termos de uso nessa página. Caso você não concorde em estar legalmente respaldado pelos seguintes termos, não acesse mais o <strong>Controle Financeiro</strong>.</p>
				<p></p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>


<!-- Arquivos JS -->
<script src="<?php echo $home ?>/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $home ?>/js/bootstrap.min.js"></script>

<!--<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-54200303-1', 'auto');
  ga('send', 'pageview');

</script>-->