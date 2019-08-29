<?php
class MyDatabase {
    public function connect(){
        return new PDO("mysql:host=localhost;dbname=web_beta;charset=utf8",'myself','HuRaIrA786(*^');
    }
    public function runSP($Q,$par,$OUT){
        $DBH = $this->connect();
        $rs = array();
        $OP = array();
        $OUTP = '';
        $OPF = '';
        $Cpar = count($par);
        $QS = '';
        for($i=0;$i<$Cpar;$i++){
            $QS .= '?,';
        }
        $QS = rtrim($QS,',');
        if(!empty($OUT)){
            foreach ($OUT as $val) {
                $OUTP .= ','.$val;
                $OPF .= $val.',';
            }
            unset($val);
        }
        $OPF = rtrim($OPF,',');
        $stmt = $DBH->prepare('CALL '.$Q.'('.$QS.$OUTP.')');
        foreach($par as $key => $val){
            $stmt->bindValue($key+1,$val);
        }
        unset($val);
        $stmt->execute();
        do{
            $rs[] = $stmt->fetchALL(PDO::FETCH_ASSOC);
        } while($stmt->nextRowset());
        $stmt->closeCursor();
        array_pop($rs);
        if(!empty($OUTP)){
            $OP = $DBH->query("SELECT $OPF")->fetch(PDO::FETCH_ASSOC);
            array_push($rs,$OP);
        }
        $DBH = null;
        return $rs;
    }
    public function db_operation($Q,$par,$OUT,&$st){
        $st=false;
        $DBH = $this->connect();
        $DBH->beginTransaction();
        $Cpar = count($par);
        $rs = array();
        $QS = '';
        $OUTP = '';
        $OPF = '';
        for($i=0;$i<$Cpar;$i++){
            $QS .= '?,';
        }
        $QS = rtrim($QS,',');
        if(!empty($OUT)){
            foreach ($OUT as $val) {
                $OUTP .= ','.$val;
                $OPF .= $val.',';
            }
            unset($val);
        }
        $OPF = rtrim($OPF,',');
        $stmt = $DBH->prepare('CALL '.$Q.'('.$QS.$OUTP.')');
        foreach($par as $key => $val){
            $stmt->bindValue($key+1,$val);
        }
        unset($val);
        if($stmt->execute()){
            $DBH->commit();
            $stmt->closeCursor();
            if(!empty($OUTP)){
                $OP = $DBH->query("SELECT $OPF")->fetch(PDO::FETCH_NUM);
                array_push($rs,$OP);
            }
            $DBH = null;
            $st = true;
            return $rs;
        } else {
            $DBH->rollBack();
            return $rs;
        }
    }
}