<?php
////////////////////////////////////////////////////////////////////////////////
//BOCA Online Contest Administrator
//    Copyright (C) 2003-2012 by BOCA Development Team (bocasystem@gmail.com)
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
////////////////////////////////////////////////////////////////////////////////
// Last modified 05/aug/2012 by cassio@ime.usp.br
//Change list:
// 02/jul/2006 by cassio@ime.usp.br
// 25/aug/2007 by cassio@ime.usp.br: php initial tag changed to complete form

require 'header.php';

if (isset($_GET["new"]) && $_GET["new"]=="1") {
	$n = DBNewContest();
	ForceLoad("contest.php?contest=$n");
}

if (isset($_GET["contest"]) && is_numeric($_GET["contest"]))
  $contest=$_GET["contest"];
else
  $contest=$_SESSION["usertable"]["contestnumber"];

if(($ct = DBContestInfo($contest)) == null)
	ForceLoad("../index.php");
if ($ct["contestlocalsite"]==$ct["contestmainsite"]) $main=true; else $main=false;

if (isset($_POST["Submit3"]) && isset($_POST["penalty"]) && is_numeric($_POST["penalty"]) &&
    isset($_POST["maxfilesize"]) && isset($_POST["mainsite"]) && isset($_POST['localsite']) &&
    isset($_POST["name"]) && $_POST["name"] != "" && isset($_POST["lastmileanswer"]) &&
    is_numeric($_POST["lastmileanswer"]) && is_numeric($_POST["mainsite"]) && is_numeric($_POST['localsite']) &&
    isset($_POST["lastmilescore"]) && is_numeric($_POST["lastmilescore"]) && isset($_POST["duration"]) &&
    is_numeric($_POST["duration"]) &&
    isset($_POST["startdateh"]) && $_POST["startdateh"] >= 0 && $_POST["startdateh"] <= 23 &&
    isset($_POST["contest"]) && is_numeric($_POST["contest"]) &&
    isset($_POST["startdatemin"]) && $_POST["startdatemin"] >= 0 && $_POST["startdatemin"] <= 59 &&
    isset($_POST["startdated"]) && isset($_POST["startdatem"]) && isset($_POST["startdatey"]) &&
    checkdate($_POST["startdatem"], $_POST["startdated"], $_POST["startdatey"])) {
	if ($_POST["confirmation"] == "confirm") {
		$t = mktime ($_POST["startdateh"], $_POST["startdatemin"], 0, $_POST["startdatem"],
                             $_POST["startdated"], $_POST["startdatey"]);
		if ($_POST["Submit3"] == "Activate") $ac=1;
		else $ac=0;
		$param['number']=$_POST["contest"];
		$param['name']=$_POST["name"];
		$param['startdate']=$t;
		$param['duration']=$_POST["duration"]*60;
		$param['lastmileanswer']=$_POST["lastmileanswer"]*60;
		$param['lastmilescore']= $_POST["lastmilescore"]*60;
		$param['penalty']=$_POST["penalty"]*60;
		$param['maxfilesize']=$_POST["maxfilesize"]*1000;
		$param['active']=$ac;
		$param['mainsite']=$_POST["mainsite"];
		$param['localsite']=$_POST["localsite"];
		$param['mainsiteurl']=$_POST["mainsiteurl"];

		DBUpdateContest ($param);
		if ($ac == 1 && $_POST["contest"] != $_SESSION["usertable"]["contestnumber"]) {
			$cf = globalconf();
			if($cf["basepass"] == "")
				MSGError("You must log in the new contest. The standard admin password is empty (if not changed yet).");
			else
				MSGError("You must log in the new contest. The standard admin password is " . $cf["basepass"] . " (if not changed yet).");

			ForceLoad("../index.php");
		}
	}
	ForceLoad("contest.php?contest=".$_POST["contest"]);
}
?>
<style>
  body {
    background: linear-gradient(to bottom, #fef3c7, #fef6e4);
    min-height: 100vh;
    margin: 0;
    font-family: sans-serif;
  }
  .card {
    background-color: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin: 2rem auto;
    max-width: 1200px;
  }
  .card-header {
    background: #b45309;
    color: white;
    padding: 1rem;
    text-align: center;
  }
  .card-title {
    font-weight: bold;
    font-size: 1.25rem;
  }
  .card-content {
    padding: 1.5rem;
    overflow-x: auto;
  }
  .form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem 1.5rem;
  }
  @media (min-width: 768px) {
    .form-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .md-col-span-2 {
      grid-column: span 2 / span 2;
    }
    .md-col-span-3 {
      grid-column: span 3 / span 3;
    }
  }
  .form-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .form-label {
    width: 8rem;
    font-size: 0.95rem;
    color: #6b7280;
  }
  .form-input, .form-select {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    padding: 0.25rem 0.5rem;
    height: 2rem;
    font-size: 1rem;
    width: 100%;
    box-sizing: border-box;
  }
  .form-input:focus, .form-select:focus {
    outline: none;
    border-color: #b45309;
  }
  .btn-outline {
    border: 1px solid #ccc;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    background-color: transparent;
    font-size: 0.95rem;
    cursor: pointer;
    margin-left: 0.5rem;
  }
  .btn-primary {
    border: 1px solid #b45309;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    background-color: #b45309;
    color: white;
    font-size: 0.95rem;
    cursor: pointer;
    margin-left: 0.5rem;
  }
  .text-muted-foreground {
    color: #6b7280;
    font-size: 0.9rem;
  }
</style>

<div class="flex min-h-screen items-center justify-center bg-gradient-to-b from-amber-50 to-amber-100 p-2">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Contest Configuration</div>
    </div>
    <div class="card-content">
      <form name="form1" enctype="multipart/form-data" method="post" action="contest.php">
        <input type="hidden" name="confirmation" value="noconfirm" />
        <script language="javascript">
          function conf() {
            if (confirm("Confirm?")) {
              document.form1.confirmation.value='confirm';
            }
          }
          function newcontest() {
            document.location='contest.php?new=1';
          }
          function contestch(n) {
            if(n==null) {
              k=document.form1.contest[document.form1.contest.selectedIndex].value;
              if(k=='new') newcontest();
              else document.location='contest.php?contest='+k;
            } else {
              document.location='contest.php?contest='+n;
            }
          }
        </script>
        <div class="form-grid">
          <!-- El selector de concursos siempre se muestra -->
          <div class="form-row md-col-span-3">
            <label for="contestNumber" class="form-label">Contest number:</label>
            <select id="contestNumber" name="contest" class="form-select" onChange="contestch()">
              <?php
              $cs = DBAllContestInfo();
              $isfake=false;
              for ($i=0; $i<count($cs); $i++) {
                echo "<option value=\"" . $cs[$i]["contestnumber"] . "\" ";
                if ($contest == $cs[$i]["contestnumber"]) {
                  echo "selected";
                  if($cs[$i]["contestnumber"] == 0) $isfake=true;
                }
                echo ">" . $cs[$i]["contestnumber"] . ($cs[$i]["contestactive"]=="t"?"*":"") ."</option>\n";
              }
              ?>
              <option value="new">new</option>
            </select>
          </div>

          <?php if($isfake) { ?>
            <!-- Si es un concurso fake, solo mostrar el mensaje -->
            <div class="form-row md-col-span-3 text-center">
              <p style="width: 100%; margin: 1.5rem 0; text-align: center;">Select a contest or create a new one.</p>
            </div>
          <?php } else { ?>
            <!-- Si no es fake, mostrar el resto del formulario -->
            <div class="form-row md-col-span-2">
              <label for="name" class="form-label">Name:</label>
              <input id="name" type="text" <?php if(!$main) echo "readonly"; ?> name="name" value="<?php echo $ct["contestname"]; ?>" class="form-input" maxlength="50" />
            </div>

            <!-- Row 2 -->
            <div class="form-row">
              <label class="form-label">Start date:</label>
              <span class="text-xs">hh:mm</span>
              <input type="text" <?php if(!$main) echo "readonly"; ?> name="startdateh" value="<?php echo date("H", $ct["conteststartdate"]); ?>" class="form-input" size="2" maxlength="2" style="width:2.5rem;" />
              <span>:</span>
              <input type="text" <?php if(!$main) echo "readonly"; ?> name="startdatemin" value="<?php echo date("i", $ct["conteststartdate"]); ?>" class="form-input" size="2" maxlength="2" style="width:2.5rem;" />
            </div>
            <div class="form-row md-col-span-2">
              <span class="text-xs">dd/mm/yyyy</span>
              <input type="text" <?php if(!$main) echo "readonly"; ?> name="startdated" value="<?php echo date("d", $ct["conteststartdate"]); ?>" class="form-input" size="2" maxlength="2" style="width:2.5rem;" />
              <span>/</span>
              <input type="text" <?php if(!$main) echo "readonly"; ?> name="startdatem" value="<?php echo date("m", $ct["conteststartdate"]); ?>" class="form-input" size="2" maxlength="2" style="width:2.5rem;" />
              <span>/</span>
              <input type="text" <?php if(!$main) echo "readonly"; ?> name="startdatey" value="<?php echo date("Y", $ct["conteststartdate"]); ?>" class="form-input" size="4" maxlength="4" style="width:3.5rem;" />
            </div>

            <!-- Row 3 -->
            <div class="form-row">
              <label for="duration" class="form-label">Duration (min):</label>
              <input id="duration" type="text" name="duration" <?php if(!$main) echo "readonly"; ?> value="<?php echo $ct["contestduration"]/60; ?>" class="form-input" maxlength="20" />
            </div>
            <div class="form-row">
              <label for="lastmileanswer" class="form-label">Stop answering:</label>
              <input id="lastmileanswer" type="text" name="lastmileanswer" <?php if(!$main) echo "readonly"; ?> value="<?php echo $ct["contestlastmileanswer"]/60; ?>" class="form-input" maxlength="20" />
            </div>
            <div class="form-row">
              <label for="lastmilescore" class="form-label">Stop scoreboard:</label>
              <input id="lastmilescore" type="text" name="lastmilescore" <?php if(!$main) echo "readonly"; ?> value="<?php echo $ct["contestlastmilescore"]/60; ?>" class="form-input" maxlength="20" />
            </div>

            <!-- Row 4 -->
            <div class="form-row">
              <label for="penalty" class="form-label">Penalty (min):</label>
              <input id="penalty" type="text" name="penalty" <?php if(!$main) echo "readonly"; ?> value="<?php echo $ct["contestpenalty"]/60; ?>" class="form-input" maxlength="20" />
            </div>
            <div class="form-row md-col-span-2">
              <label for="maxfilesize" class="form-label">Max file size (KB):</label>
              <input id="maxfilesize" type="text" name="maxfilesize" <?php if(!$main) echo "readonly"; ?> value="<?php echo $ct["contestmaxfilesize"]/1000; ?>" class="form-input" maxlength="20" />
            </div>

            <!-- Row 5 -->
            <div class="md-col-span-3">
              <p class="text-muted-foreground">
                Your PHP config. allows at most: <?php echo ini_get('post_max_size').'B(max. post) and '.ini_get('upload_max_filesize').'B(max. filesize)'; ?>
              </p>
            </div>

            <!-- Row 6 -->
            <div class="form-row md-col-span-3">
              <label for="mainsiteurl" class="form-label">Main site URL:</label>
              <input id="mainsiteurl" type="text" name="mainsiteurl" value="<?php echo $ct["contestmainsiteurl"]; ?>" class="form-input" maxlength="200" />
            </div>

            <!-- Row 7 -->
            <div class="form-row">
              <label for="mainsite" class="form-label">Main site number:</label>
              <input id="mainsite" type="text" name="mainsite" value="<?php echo $ct["contestmainsite"]; ?>" class="form-input" maxlength="4" />
            </div>
            <div class="form-row">
              <label for="localsite" class="form-label">Local site number:</label>
              <input id="localsite" type="text" name="localsite" value="<?php echo $ct["contestlocalsite"]; ?>" class="form-input" maxlength="4" />
            </div>

            <!-- Row 8: Botones -->
            <div class="form-row md-col-span-3" style="justify-content: flex-end; gap: 0.5rem;">
              <input type="reset" name="Submit4" value="Clear" class="btn-outline">
              <input type="submit" name="Submit3" value="Activate" class="btn-outline" onClick="conf()">
              <input type="submit" name="Submit3" value="Send" class="btn-primary" onClick="conf()">
            </div>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
