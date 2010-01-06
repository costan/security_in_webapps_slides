<?php

// Unix domain socket client

class UnixClient {
  var $pn = null;

  function UnixClient($pn) {
    $this->pn = $pn;
  }

  function call($req) {
    $sock = fsockopen("unix://" . $this->pn);
    fwrite($sock, $req);
    $resp = trim(fgets($sock));
    fclose($sock);
    return $resp;
  }
}

?>
