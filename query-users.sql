SELECT DISTINCT users.uid
FROM users, note NATURAL JOIN schedules
WHERE
(
  note.nid = 1
  AND (getDistance(users.latitude, users.longitude, note.latitude, note.longitude) <= note.radius)
  AND (withinSchedule('Sun', '2018-11-10', '15:00:00', schedules.activeDays, schedules.startDate, schedules.endDate, schedules.startTime, schedules.endTime) = 'true')
  AND ((note.notePrivacy = 'self' AND users.uid = note.uid) OR
  (note.notePrivacy = 'friends' AND EXISTS (SELECT * FROM Friendship WHERE users.uid = friendship.uid AND note.uid = friendship.friends_uid)) OR (note.notePrivacy = 'public'))
  AND users.uid NOT IN

  (
    SELECT DISTINCT users.uid
    FROM users NATURAL JOIN state, filters NATURAL JOIN schedules, note NATURAL JOIN tag_in_note NATURAL JOIN tag
    WHERE
    (
      note.nid = 1
      AND ((state.isActive = 'true' AND filters.sid = state.sid) OR (users.uid = filters.uid AND filters.sid IS NULL))
      AND ((filters.radius IS NOT NULL AND (getDistance(users.latitude, users.longitude, filters.latitude, filters.longitude) >= filters.radius))
      OR (filters.sched_id IS NOT NULL AND (withinSchedule('Sun', '2018-11-10', '15:00:00', schedules.activeDays, schedules.startDate, schedules.endDate, schedules.startTime, schedules.endTime) = 'false'))
      OR (tag.tid != filters.tid AND filters.tid IS NOT NULL)
      OR (filters.filter_privacy IS NOT NULL AND (note.notePrivacy NOT LIKE (filters.filter_privacy))))
    )
  )
)
