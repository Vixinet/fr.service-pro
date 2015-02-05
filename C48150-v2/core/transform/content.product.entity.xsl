<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		
    <h1>Produits</h1>
		
		<form class="form-horizontal product-listing" onsubmit="return false;">
			<div class="input-append">
				<input class="span2" id="prependedInput" name="sku"  type="text" placeholder="Référence" />
				<button class="btn btn-product-add" type="button">Ajouter</button>
			</div>
		</form>
		
		<div class="products-table">
			<div class="text-center">
				<img src="res/images/loader.gif" />
			</div>
		</div>
		
		<script>
			cbProductContentChangePage();
		</script>
  </xsl:template>
	
</xsl:stylesheet>