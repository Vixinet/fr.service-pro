<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		<xsl:if test="count(/data/transform/pages/page) &gt; 1">
			<div class="pagination pagination-mini pagination-centered">
				<ul class="product-entity">
					<xsl:apply-templates select="/data/transform/pages/page" />
				</ul>
			</div>
		</xsl:if>
		
    <table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Produit</th>
					<th class="span1">Prix</th>
					<th class="span1">Achat</th>
					<th class="span1">Stock</th>
					<th class="span1">Offres</th>
					<th class="span3"> </th>
				</tr>
			</thead>
			<tbody>
				<xsl:if test="count(/data/transform/product) = 0">
					<tr><td colspan="6"><p class="text-center" style="margin:0px">Aucun produit</p></td></tr>
				</xsl:if>
				<xsl:apply-templates select="/data/transform/product" />
			</tbody>
		</table>
		
		<xsl:if test="count(/data/transform/pages/page) &gt; 1">
			<div class="pagination pagination-mini pagination-centered">
				<ul class="product-entity">
					<xsl:apply-templates select="/data/transform/pages/page" />
				</ul>
			</div>
		</xsl:if>
  </xsl:template>
  
	<xsl:template match="page">
		<li>
			<xsl:if test="@active = 1">
				<xsl:attribute name="class">active</xsl:attribute>
			</xsl:if>
			<a href="#">
				<xsl:value-of select="." />
			</a>
		</li>
	</xsl:template>
	
	<xsl:template match="product">
		<tr>
			<td>
				<input type="hidden" value="{sku}" name="sku_ref" />
				<input type="text" value="{sku}" name="sku" class="span3"/>
			</td>
			<td><input type="text" value="{price_base}" name="price_base" class="span1"/></td>
			<td><input type="text" value="{price_buy}" name="price_buy" class="span1"/></td>
			<td><input type="text" value="{stock}" name="stock" class="span1"/></td>
			<td><xsl:value-of select="pm_offers" /></td>
			<td>
				<button class="btn btn-product-edit">Editer</button>
				<button class="btn btn-product-open" onclick="contentProductEntity_loadInfo('{sku}')">Ouvrir</button>
				<button class="btn btn-product-delete">Sup.</button>
			</td>
		</tr>
	</xsl:template>
	
</xsl:stylesheet>