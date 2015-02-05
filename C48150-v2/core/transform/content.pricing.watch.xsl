<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		
		<h1>Vue d'ensemble</h1>
		
		<h4>Administration</h4>
		<div>
			<button class="btn span3 btn-offers-import">Importer mes offres</button>
			<button class="btn span3 btn-watch-reset">Relancer le calcul </button>
			<button class="btn span3 btn-pm-upd-pricing">Importer prix concurrents</button><br/><br/>
		</div>
		
		<h4>En cours</h4>
		<table class="table table-bordered">
			<tr>
				<td rowspan="3" class="span3" style="text-align:center;vertical-align:middle;">
					<canvas id="cnvItem1" height="100" width="100"></canvas>
					<script>
						var doughnutData = [
							{ value: <xsl:value-of select="/data/transform/progress/done_p" />, color:"#375D81" },
							{ value : <xsl:value-of select="/data/transform/progress/todo_p" />, color : "#8CC6D7" }
						];
						var myDoughnut = new Chart(document.getElementById("cnvItem1").getContext("2d")).Doughnut(doughnutData);
					</script>
				</td>
				<th>Total des offres</th>
				<th class="span2"><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/offers" /></p></th>
				<th class="span2"><p class=" text-right" style="margin:0px"> </p></th>
			</tr>
			<tr>
				<td><span class="badge" style="background:#375D81">&#160;</span> Offres effectuées</td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/done" /></p></td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/done_p" /> %</p></td>
			</tr>            
			<tr>             
				<td>
					<span class="badge" style="background:#8CC6D7">&#160;</span>
					<xsl:text> </xsl:text>
					En cours
					<xsl:text> - </xsl:text>
					ETA
					<xsl:value-of select="/data/transform/progress/todo_eta_h" /> h
					<xsl:value-of select="/data/transform/progress/todo_eta_m" /> min
					<xsl:value-of select="/data/transform/progress/todo_eta_s" /> sec
				</td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/todo" /></p></td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/todo_p" /> %</p></td>
			</tr>
		</table>
		
		<h4>Situation actuelle</h4>
		<table class="table table-bordered">
			<tr>
				<td rowspan="4" class="span3" style="text-align:center;vertical-align:middle;">
					<canvas id="cnvItem2" height="100" width="100"></canvas>
					<script>
						var doughnutData = [
							{ value: <xsl:value-of select="/data/transform/progress/owned_p" />, color:"#CD7F32" },
							{ value : <xsl:value-of select="/data/transform/progress/toprice_p" />, color : "#FDB45C" },
							{ value : <xsl:value-of select="/data/transform/progress/na_p" />, color : "#FFDA8C" }
						];
						var myDoughnut = new Chart(document.getElementById("cnvItem2").getContext("2d")).Doughnut(doughnutData);
					</script>
				</td>
				<th>Offres terminées</th>
				<th class="span2"><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/done" /></p></th>
				<th class="span2"><p class=" text-right" style="margin:0px"> </p></th>
			</tr>
			<tr>
				<td><span class="badge" style="background:#CD7F32">&#160;</span> Vos offres</td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/owned" /></p></td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/owned_p" /> %</p></td>
			</tr>
			<tr>
				<td><span class="badge" style="background:#FDB45C">&#160;</span> Compétition</td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/toprice" /></p></td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/toprice_p" /> %</p></td>
			</tr>
			<tr>
				<td><span class="badge" style="background:#FFDA8C">&#160;</span> Aucune mise en avant</td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/na" /></p></td>
				<td><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/na_p" /> %</p></td>
			</tr>
		</table>
		
		<h4>Compétition</h4>
		<table class="table table-bordered">
			<tr>
				<td class="span3" rowspan="{count(/data/transform/competitors/competitor)+2}" style="text-align:center;vertical-align:middle;">
					<canvas id="cnvItem3" height="200" width="200"></canvas>
					<script>
						var doughnutData = [
							<xsl:for-each select="/data/transform/competitors/competitor">
								{ value: <xsl:value-of select="offers_p" />, color:"<xsl:value-of select="color" />" }
								<xsl:if test="position() != last()">
									<xsl:text>,</xsl:text>
								</xsl:if>
							</xsl:for-each>
						];
						var myDoughnut = new Chart(document.getElementById("cnvItem3").getContext("2d")).Doughnut(doughnutData);
					</script>
				</td>
				<th>Offres en compétition</th>
				<th class="span2"><p class=" text-right" style="margin:0px"><xsl:value-of select="/data/transform/progress/toprice" /></p></th>
				<th class="span2"><p class=" text-right" style="margin:0px"> </p></th>
			</tr>
			<xsl:for-each select="/data/transform/competitors/competitor">
				<xsl:sort data-type="number" select="offers" order="descending" />
				<tr>
					<td><span class="badge" style="background:{color}">&#160;</span><xsl:text> </xsl:text><xsl:value-of select="name"/></td>
					<td><p class=" text-right" style="margin:0px"><xsl:value-of select="offers" /></p></td>
					<td><p class=" text-right" style="margin:0px"><xsl:value-of select="offers_p" /> %</p></td>
				</tr>
			</xsl:for-each>
		</table>
  </xsl:template>
  
</xsl:stylesheet>