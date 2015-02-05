<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0"
                xmlns:x="http://www.cdiscount.com"
                xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes="x s">
  
  <xsl:output method="xml" indent="yes" />
  
  <xsl:template match="/">
    <response>
      <xsl:apply-templates select="//x:Order" />
    </response>
  </xsl:template>
  
  <xsl:template match="x:Order">
    <order state="{x:OrderState}"><xsl:value-of select="x:OrderNumber" /></order>
  </xsl:template>
  
</xsl:stylesheet>