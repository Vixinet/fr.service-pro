<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:include href="lib.common.xsl" />
  
  <xsl:template match="/">
    <out>
      <xsl:copy-of select="/" />
      <xml>
        <xsl:call-template name="content" />
      </xml>
    </out>
  </xsl:template>
  
</xsl:stylesheet>