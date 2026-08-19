<?php

class Report
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM reports");
        return $stmt->fetchAll();
    }

    public function delete($report_id)
    {
        try {
            $this->pdo->beginTransaction();

            // Delete from reports table
            $stmt1 = $this->pdo->prepare("DELETE FROM reports WHERE report_id = ?");
            $stmt1->execute([$report_id]);

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to delete report: " . $e->getMessage());
        }
    }
}
