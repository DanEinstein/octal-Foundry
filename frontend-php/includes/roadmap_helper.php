<?php
/**
 * Roadmap Helper Functions
 * Handles database operations for units, roadmaps, and videos
 */

require_once __DIR__ . '/db.php';

/**
 * Save a new unit to the database
 */
function createUnit(int $userId, string $code, string $name, string $lecturer, string $semester, int $year): ?int {
    $db = db();
    try {
        $stmt = $db->prepare(
            "INSERT INTO units (user_id, unit_code, unit_name, lecturer_name, semester, year, status) 
             VALUES (?, ?, ?, ?, ?, ?, 'in_progress')"
        );
        $stmt->execute([$userId, $code, $name, $lecturer, $semester, $year]);
        return (int)$db->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating unit: " . $e->getMessage());
        return null;
    }
}

/**
 * Save an AI-generated roadmap to the database
 */
function saveRoadmapToDatabase(int $unitId, array $roadmapData): bool {
    $db = db();
    $db->beginTransaction();
    
    try {
        foreach ($roadmapData as $week) {
            // 1. Insert Roadmap Week
            $stmt = $db->prepare(
                "INSERT INTO roadmaps (unit_id, week_number, week_title, week_description, project_task, topics, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            
            $status = ($week['week'] == 1) ? 'current' : 'locked';
            $topicsJson = json_encode($week['topics'] ?? []);
            
            $stmt->execute([
                $unitId,
                $week['week'],
                $week['title'],
                $week['description'] ?? '',
                $week['project_task'] ?? null,
                $topicsJson,
                $status
            ]);
            
            $roadmapId = $db->lastInsertId();
            
            // 2. Insert Videos for this week
            if (!empty($week['videos'])) {
                $videoStmt = $db->prepare(
                    "INSERT INTO videos (roadmap_id, video_id, title, channel_name, thumbnail_url, duration, view_count, description, position)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                
                foreach ($week['videos'] as $index => $video) {
                    $videoStmt->execute([
                        $roadmapId,
                        $video['video_id'],
                        $video['title'],
                        $video['channel'] ?? '',
                        $video['thumbnail'] ?? '',
                        $video['duration'] ?? '',
                        $video['views'] ?? '',
                        $video['description'] ?? '',
                        $index + 1
                    ]);
                }
            }
        }
        
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Error saving roadmap: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all units for a specific user
 */
function getUserUnits(int $userId): array {
    $db = db();
    $stmt = $db->prepare(
        "SELECT * FROM units WHERE user_id = ? ORDER BY created_at DESC"
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/**
 * Get a specific unit by ID (and verify ownership)
 */
function getUnit(int $unitId, int $userId): ?array {
    $db = db();
    $stmt = $db->prepare("SELECT * FROM units WHERE id = ? AND user_id = ?");
    $stmt->execute([$unitId, $userId]);
    $unit = $stmt->fetch();
    return $unit ?: null;
}

/**
 * Get the full 12-week roadmap for a unit
 */
function getUnitRoadmap(int $unitId): array {
    $db = db();
    $stmt = $db->prepare(
        "SELECT * FROM roadmaps WHERE unit_id = ? ORDER BY week_number ASC"
    );
    $stmt->execute([$unitId]);
    return $stmt->fetchAll();
}

/**
 * Get videos for a specific roadmap week
 */
function getWeekVideos(int $roadmapId): array {
    $db = db();
    $stmt = $db->prepare(
        "SELECT * FROM videos WHERE roadmap_id = ? ORDER BY position ASC"
    );
    $stmt->execute([$roadmapId]);
    return $stmt->fetchAll();
}

/**
 * Get the current active week for a unit
 */
function getCurrentWeek(int $unitId): ?array {
    $db = db();
    $stmt = $db->prepare(
        "SELECT * FROM roadmaps WHERE unit_id = ? AND status = 'current' LIMIT 1"
    );
    $stmt->execute([$unitId]);
    $week = $stmt->fetch();
    return $week ?: null;
}
