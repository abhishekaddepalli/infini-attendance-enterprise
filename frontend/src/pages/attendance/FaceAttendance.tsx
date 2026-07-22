import React from 'react';

export default function FaceAttendance() {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold text-slate-800">AI Face Recognition Attendance</h1>
      <p className="text-slate-500 mb-6">Real-time facial verification and liveness detection</p>
      <div className="bg-slate-900 rounded-xl h-96 flex items-center justify-center text-slate-400">
        Camera Feed & Liveness Detection Active
      </div>
    </div>
  );
}
