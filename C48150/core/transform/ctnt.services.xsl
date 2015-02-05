<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:include href="lib.common.xsl" />
  <xsl:include href="page.load-content.xsl" />
  
  <xsl:template name="content">
    <h1>Services</h1>
    <form class="form-horizontal services" onsubmit="return false;">
      <div class="control-group">
        <label class="control-label" for="inputEmail">Services Definitions</label>
        <div class="controls">
          <div class="input-append">
            <input type="text" name="url-services" class="input-block-level input-xxlarge"
                   value="http://svpro.fr/wbcom/admin/core/service.definitions.xml" />
            <button type="button" class="btn url-services">Get</button>
          </div>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">Services List</label>
        <div class="controls">
          <select name="services" multiple="multiple"></select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">Service</label>
        <div class="controls">
          <button type="button" class="btn disabled load-service">Load service</button>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">Service parameters</label>
        <div class="controls">
          <table class="params service-params table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Input</th>
                <th>Send</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th colspan="5">Load service before</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">Global parameters</label>
        <div class="controls">
          <table class="params global-params table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Input</th>
                <th>Send</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="name">Service name</td>
                <td class="field">
                  <input name="service" type="text" class="input-block-level input-xxlarge uneditable-input" />
                </td>
                <td class="send">
                  <input type="checkbox" checked="checked" disabled="disabled" />
                </td>
              </tr>
              <tr>
                <td class="name">Ommit header</td>
                <td class="field">
                  <select name="ommit_header" class="input-block-level input-xxlarge">
                    <option value="true">Yes</option>
                    <option value="">No</option>
                  </select>
                </td>
                <td class="send">
                  <input type="checkbox" checked="checked" disabled="disabled" />
                </td>
              </tr>
              <tr>
                <td class="name">Request type</td>
                <td class="field">
                  <select name="request_type" class="input-block-level input-xxlarge">
                    <option>GET</option>
                    <option>POST</option>
                  </select>
                </td>
                <td class="send">
                  <input type="checkbox" disabled="disabled" />
                </td>
              </tr>
              <tr>
                <td class="name">Call url</td>
                <td class="field">
                  <input type="text"
                         class="input-block-level input-xxlarge"
                         name="url-service"
                         value="http://svpro.fr/wbcom/admin/core/xhr.admin.wrapper.php" />
                </td>
                <td class="send">
                  <input type="checkbox" disabled="disabled" />
                </td>
              </tr>
              <tr>
                <td colspan="3" class="text-center">
                  <button type="button" class="btn disabled btn-primary request-service">Request</button>
                </td>
              </tr>
              
            </tbody>
          </table>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEmail">Response</label>
        <div class="controls">
          <pre class="pre-scrollable response">&gt; Send a request before</pre>
        </div>
      </div>
    </form>
  </xsl:template>
  
</xsl:stylesheet>