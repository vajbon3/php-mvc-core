<?php


namespace app\core\db;

use app\core\Application;

class Database
{
    public \PDO $pdo;
    /**
     * Database constructor.
     */
    public function __construct(array $config)
    {
        $dsn = $config["dsn"] ?? "";
        $user = $config["user"] ?? "";
        $password = $config["password"] ?? "";
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations() {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$app->ROOT_DIR."/migrations");
        $unappliedMigrations = array_diff($files, $appliedMigrations);

        $newMigrations = [];
        foreach($unappliedMigrations as $migration) {
            if($migration === "." || $migration === "..")
                continue;

            require_once Application::$app->ROOT_DIR."/migrations/".$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);

            $this->log("Applying Migration $migration");
            $instance = new $className();
            $instance->up();
            $this->log("Applied migrations $migration");
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable() {
        $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;
        ");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations) {
        $str = implode(",",array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");
        $statement->execute();
    }

    protected function log($message) {
        echo "[".date("Y-m-D H:i:s"). "] - ".$message.PHP_EOL;
    }
}