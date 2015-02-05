<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		<h2><xsl:value-of select="/data/transform/product/sku" /></h2>
		
		<h2>Offres</h2>
		<form class="form-horizontal product-entity" onsubmit="return false;">
			<input type="hidden" name="sku" value="{/data/transform/product/sku}" />
			<button class="btn btn-pm-set-stock">MAJ du stock = <xsl:value-of select="/data/transform/product/stock"/></button>
		</form>
    <table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Offre</th>
					<th>Pack</th>
					<th>ID</th>
					<th>Prix</th>
				</tr>
			</thead>
			<tbody>
				<xsl:if test="count(/data/transform/offers_pm/offer) = 0">
					<tr><td colspan="4"><p class="text-center" style="margin:0px">Aucune offre</p></td></tr>
				</xsl:if>
				<xsl:apply-templates select="/data/transform/offers_pm/offer" />
			</tbody>
		</table>
  </xsl:template>
  
	<xsl:template match="offer">
		<tr>
			<td>
				<a target="_blank" href="http://priceminister.com{url}"><xsl:value-of select="label" /></a><br/>
				<xsl:value-of select="description" />
			</td>
			<td><xsl:value-of select="pack" /></td>
			<td class="span3">
				<strong>AID: </strong><xsl:value-of select="advertid" /><br/>
				<strong>Mise à jour: </strong><xsl:value-of select="date_upd" />
			</td>
			<td class="span2">
				<strong>Prix: </strong><xsl:value-of select="price" /><br/>
				<strong>Expédition: </strong><xsl:value-of select="shipping" />
			</td>
		</tr>
	</xsl:template>
	
</xsl:stylesheet>