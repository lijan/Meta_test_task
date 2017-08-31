<?php if (!isset($_GET['task']) || empty($_GET['task'])): ?>
    <?php die();?>
<?php endif ?>

<?php
/**
 * 1. Составить программу, которая проверяет корректность баланса скобок в арифметическом выражении, т.е. что скобки установлены верно и правильно их вхождение, то есть если скобки так расположены [({})] , то это правильное вхождение, а вот [([) - неверное.
 * Входной параметр - Строка - арифметическое выражение;
 * Выходной параметр - "Верно";"Не верно".
 */

class BracketsClass {
    public $input    = '';
    public $brackets = [
        '[' => ']',
        '(' => ')',
        '{' => '}',
    ];
    public $result;
    public $errors     = [];
    public $matchings  = [];
    public $expression = '';

    public function loadExpression($s = '') {
        for ($i = 0; $i < strlen($s); $i++) {
            if (!array_key_exists($s[$i], $this->brackets) && !in_array($s[$i], $this->brackets)) {
                continue; // not a bracket
            }
            if (in_array($s[$i], $this->brackets) || array_key_exists($s[$i], $this->brackets)) {
                $this->expression .= $s[$i];
            }
        }
        $this->result     = $this->checkBrackets();
        $this->expression = '';
    }

    protected function checkBrackets() {
        $result = true;
        // echo "INPUT: {$this->expression}<br/>";
        foreach ($this->brackets as $_o => $_c) {
            $count_o = substr_count($this->expression, $_o);
            $count_c = substr_count($this->expression, $_c);
            if ($count_o > $count_c) {
                $this->errors[] = "Закрывающих скобок типа '{$_o}{$_c}' меньше открывающих.";
                return false;
            }
            if ($count_o < $count_c) {
                $this->errors[] = "Закрывающих скобок типа '{$_o}{$_c}' больше открывающих.";
                return false;
            }
        }
        $last_o = strlen($this->expression) + 1;
        $___    = [];
        for ($i = strlen($this->expression) / 2; $i > 0; $i--) {
            $__ = [];
            foreach (array_keys($this->brackets) as $bracket) {
                $_ = strripos($this->expression, $bracket, -(strlen($this->expression) - $last_o + 1));
                if ($_ === FALSE) {
                    continue;
                }
                $__[] = $_;
            }
            $last_o = max($__);

            $__ = [];
            foreach (array_values($this->brackets) as $bracket) {
                $_ = strpos($this->expression, $bracket, $last_o+1); // пофиксить.
                if ($_ === FALSE) {
                    continue;
                }
                if (in_array($_, $___)) {
                    continue;
                }
                $__[] = $_;
                // if ($this->expression == '([((({})))])') {
                // }
            }
            $first_c = $___[] = min($__);

            if ($this->brackets[$this->expression[$last_o]] != $this->expression[$first_c]) {
                $this->errors[] = "Неверный символ \"{$this->expression[$first_c]}\". Возможно вы имели в виду \"{$this->brackets[$this->expression[$last_o]]}\"?";
                $result         = false;
            }
            // else {
            $this->matchings[] = "PAIR: \"{$this->expression[$last_o]}{$this->expression[$first_c]}\" {$last_o} AND {$first_c}";
            // }
        }

        return $result;
    }

    public function getResult() {
        return $this->result ? 'Верно.' : 'Не верно.';
    }
    public function getMatchings() {
        return implode("<br/>\n", $this->matchings);
    }

    public function getErrors() {
        return implode("<br/>\n", $this->errors);
    }

    public function getDebugInfo() {
        ob_start();
        $info = ob_get_clean();

        $html = "<div class=\"debug\">";
        $html .= "<h1> Debug: </h1>";
        $html .= "<pre>$info</pre>";
        $html .= "</div>";
        return $html;
    }
}
?>

<?php if ($_GET['task'] == 1): ?>
    <?php $input = [
        "(a+b)^[c:i]",
        "[b*(d-d)-a-c]+{b*[d-d]-a-c}",
        "[b*(d-d)-a-c]",
        "{b*[d-d]-a-c}",
        "(a+b)*c",
        "(a*b+{x*(2*c)})",
        "([((({})))])",
        "a*b+{x*(2*c)}",
        "a*c*(a+b^[x*y])",
        "[({})]",
        "=================MISTAKES=================",
        "(a*c*(a+b^(x*y))",
        "[b*(d]-d)-a-c",
        "a*c*(a+b^{ (x*y} })",
        "a*b+{x*2)*{c)",
        "[([)",

    ];?>
    <?php foreach ($input as $s): ?>
        <?php
            $_ = new BracketsClass;
            $_->loadExpression($s);
        ?>
        <span class="input"><?=$s;?></span>
        <div class="results">
            <div class="result"><?=$_->getMatchings();?></div>
            <div class="summary"><?=$_->getResult();?></div>
            <div class="errors"><?=$_->getErrors();?></div>
            <!-- <div class="debug-wrapper"><?=$_->getDebugInfo();?></div> -->
        </div>
        <hr>
    <?php endforeach;?>

<?php elseif ($_GET['task'] == 2): ?>

<?php
/**
 * 2. Напишите запрос, отыскивающий неуникальные значения id в таблице CREATE TABLE (id int not null, name text);
 */
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tz";

$db = new mysqli($host, $user, $pass, $db);
if ($db->connect_errno) {
    echo $db->connect_error;
}
if(!$db->query("CREATE TABLE IF NOT EXISTS `tz2` (id int NOT NULL, name text)")) {
    echo $db->error;
}

if(!$db->query("TRUNCATE TABLE `tz2`;")) {
    echo $db->error;
}
$sql = "INSERT INTO `tz2` (id, name) VALUES
    ('1', 'Абадан — Иран'),
    ('1', 'Абаза — Россия'),
    ('1', 'Абакан — Россия'),
    ('1', 'Абдулино — Россия'),
    ('1', 'Абердин — Великобритания'),
    ('1', 'Абинск — Россия'),
    ('1', 'Абовян — Армения'),
    ('1', 'Абуджа — Нигерия'),
    ('1', 'Авиньон — Франция'),
    ('1', 'Агадир — Марокко'),
    ('1', 'Агартала — Индия'),
    ('1', 'Агдам — Азербайджан'),

    ('2', 'Яблонец — Чехия'),
    ('2', 'Яхрома — Россия'),

    ('3', 'Ядрин — Россия'),
    ('4', 'Якутск — Россия'),
    ('5', 'Ялта — Украина'),
    ('6', 'Ялуторовск — Россия'),
    ('7', 'Ярцево — Россия'),
    ('8', 'Ясный — Россия'),
    ('9', 'Ясногорск — Россия'),
    ('10', 'Яунде — Камерун'),
    ('11', 'Яссы — Румыния'),

    ('1', 'Янгон — Мьянма'),
    ('1', 'Яранск — Россия'),
    ('1', 'Ярославль — Россия')
";
if(!$db->query($sql)) {
    echo $db->error;
}

$sql = "
SELECT `id`,`name`
FROM `tz2` `original`
WHERE `id` IN (
    SELECT `id`
    FROM `tz2` `double`
    GROUP BY `id`
    HAVING count(*)>1
)";

if(!$res = $db->query($sql)) {
    echo $db->error;
}
var_dump($res->fetch_all()); // <------ Неуникальные значения здесь.
?>

<?php endif ?>
